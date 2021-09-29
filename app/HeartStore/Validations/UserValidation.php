<?php
 
namespace App\HeartStore\Validations;

use App\Models\User;
use Illuminate\Validation\Rule;

class UserValidation
{
	private static $rules = [
		'username' => ['required', 'max:100'],
		'first_name' => ['required', 'max:50'],
		'last_name' => ['required', 'max:50'],
		'email' => ['required', 'email', 'max:100', 'unique:users'],
		'password' => ['required', 'max:15'],
		
	];

	private static $emailVerify = [
		'id' => ['required', 'Integer'],
		'code' => ['required', 'Integer']
	];

	private static $sendForgetPasswordCode = [
		'email' => ['required', 'email'],
	];

	private static $resetPasswordRule = [
		'password' => ['required', 'max:15'],
		'confirm_password' => ['required', 'same:password', 'max:15'],
		'code' => ['required', 'Integer'],
	];

	private static $changePasswordRules = [
		'old_password' => ['required', 'max:15'],
		'new_password' => ['required', 'max:15'],
	];

	public static function signup() 
	{
		return [ 
			'rules' => self::$rules
		];
	}

	public static function emailVerify() 
	{
		return [ 
			'rules' => self::$emailVerify
		];
	}

	public static function getSendPasswordCodeRules() 
	{
		return [ 
			'rules' => self::$sendForgetPasswordCode
		];
	}

	public static function getResetPasswordRules() 
	{
		return [ 
			'rules' => self::$resetPasswordRule
		];
	}

	public static function changePassword() 
	{
		return [ 
			'rules' => self::$changePasswordRules
		];
	}

	public static function validateEmail($validator, $attributes) 
	{
		$validator->after(function ($validator) use ($attributes) {

			$user = User::where('email', $attributes['email'])->first();

			if ($user) {
				$validator->errors()->add(
					'email', 'The email already exists.'
				);
			}
		});
	}
}