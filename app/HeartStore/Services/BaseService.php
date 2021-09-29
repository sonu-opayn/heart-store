<?php

namespace App\HeartStore\Services;

use App\Models\VerificationCode;

abstract class BaseService
{
	private function generateCode()
    {
        return $this->randomCode(4);
    }

	private function randomCode($digits)
	{
        return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }

    protected function generateVerifyCode($user)
	{
		$code = new VerificationCode();
		$code->user_id = $user->id;
		$code->code = $this->generateCode();
		$code->expiry_time = date('Y-m-d H:i:s', strtotime("now +10 minute"));
		$code->save();
		return $code;
	}
}