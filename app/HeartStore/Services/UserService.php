<?php

namespace App\HeartStore\Services;

use App\Mail\ForgetPasswordCode;
use App\HeartStore\Exceptions\GeneralException;
use App\HeartStore\Exceptions\ValidationException;
use App\HeartStore\Services\BaseService;
use App\HeartStore\Validations\UserValidation;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Models\VerificationCode;
use App\Mail\ForgotPasswordCode;

class UserService extends BaseService 
{

	public function __construct(RoleService $roleService)
	{
		$this->roleService = $roleService;
	}

	public function getUserById($id) 
	{
		return User::find($id);
	}

	/**
	 * Send Forgot Password code
	 */

	public function sendForgetPasswordCode(array $attributes) 
	{
		$validation = UserValidation::getSendPasswordCodeRules();
		$validator = Validator::make($attributes, $validation['rules']);

		if ($validator->fails()) {
			throw new ValidationException($validator);
		}

		$user = User::where('email', $attributes['email'])->first();

		if(!$user) {
			throw new GeneralException("User not found.");
		}

		Mail::to($user->email)->send(new ForgotPasswordCode([
			'user' => $user,
			'code' => ($this->generateVerifyCode($user))->code
		]));

		return true;	
	}

	/**
	 * Reset Password
	 */
	public function resetPassword(array $attributes)
	{

		$validations = UserValidation::getResetPasswordRules();
		$validator = Validator::make($attributes, $validations['rules']);
		
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}

		$inputCode = $attributes['code'];
		
		$code = VerificationCode::with('user')->where('code', $inputCode)
				->where('is_used', 0)
				->first();

		if(!$code) {
			throw new GeneralException("Code not found.");
		}
		
		$user = $code->user;

		if(!$user) {
			throw new GeneralException("Resouce not found.");
		}

		$user->password = Hash::make($attributes['password']);
		$user->save();

		//set code as used
		$code->is_used = 1;
		$code->save();
		
		$user->tokens->each(function($token, $key) {
			$token->delete();
		});

		return true;
	}

	/**
	 * Change Password
	 */
	public function changePassword(array $attributes)
	{

		$validations = UserValidation::changePassword();
		$validator = Validator::make($attributes, $validations['rules']);
		
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}

		$id = $attributes['id'];
		$user = User::find($id);

		if(!$user) {
			throw new GeneralException("Resouce not found.");
		}
		
		if(!Hash::check($attributes['old_password'], $user->password)) {
			throw new GeneralException("Current password is wrong.");
		}

		$user->password = Hash::make($attributes['new_password']);
		$user->save();
		
		$user->tokens->each(function($token, $key) {
			$token->delete();
		});

		return true;
	}

}