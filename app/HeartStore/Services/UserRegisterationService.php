<?php

namespace App\HeartStore\Services;

use App\Events\UserRegister;
use App\HeartStore\Services\BaseService;
use App\HeartStore\Exceptions\ValidationException;
use App\HeartStore\Validations\UserValidation;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\HeartStore\Services\RoleService;
use App\Models\VerificationCode;
use App\Models\UserProfile;
use App\HeartStore\Exceptions\GeneralException;
use App\HeartStore\Services\UserService;

class UserRegisterationService extends BaseService 
{
	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}
    /**
     * User Sign Up
     */
    public function userSignUp(array $attributes)
    {
        $validations = UserValidation::signup();
        $validator = Validator::make($attributes, $validations['rules']);
        
        if ($validator->fails()) {
			throw new ValidationException($validator);
		}

        $user = new User([
            'username' => $attributes['username'],
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        $user->save();

        $user->assignRole(RoleService::CUSTOMER);
        $verify = $this->generateVerifyCode($user);
        
        event(new UserRegister([
            'user' => $user,
            'code' => $verify->code
        ]));

        return $user;
    }

	/**
	 * Email Verify
	 */
    public function emailVerify(array $attributes)
	{
		$validations = UserValidation::emailVerify();
		$validator = Validator::make($attributes, $validations['rules']);
		
		if ($validator->fails()) {
			throw new ValidationException($validator);
		}

		$userId = $attributes['id'];
		$inputCode = $attributes['code'];

		$user = User::find($userId);

		if(!$user) {
			return false;
		}

		$code = VerificationCode::where('user_id', $user->id)
				->where('code', $inputCode)
				->where('is_used', 0)
				->first();

		if(!$code) {
			return false;
		}

		if($code->code == (int) $inputCode) {

			//set email verification date
			$user->email_verified_at = date('Y-m-d H:i:s', strtotime('now'));
			$user->save();

			//Create Profile at this point
			$userProfile = new UserProfile();
			$userProfile->user_id = $user->id;
			$userProfile->save();

			//set code as used
			$code->is_used = 1;
			$code->save();
			
			return true;
		} 
		
		return false;
	}

	/**
	 * Resend Verification Code
	 */
	public function resendEmailVerficationCode(array $attributes)
	{
		if(!ine($attributes, 'id')) {
			throw new GeneralException("Id parameter is missing.");
		}

		$userId = $attributes['id'];
		
		$user = $this->userService->getUserById($userId);

		if(!$user) {
			throw new GeneralException('User not found.');
		}

		$verify = $this->generateVerifyCode($user);

		event(new UserRegister([
			'user' => $user,
			'code' => $verify->code
		]));
		
		return true;	
	}
}