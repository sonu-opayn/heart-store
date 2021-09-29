<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HeartStore\Services\UserRegisterationService;
use Illuminate\Support\Facades\Log;
use App\HeartStore\ApiResponse;
use App\HeartStore\Exceptions\ValidationException;
use App\HeartStore\Exceptions\GeneralException;

class UserRegisterationController extends Controller
{
    public function __construct(UserRegisterationService $userRegisterationService)
    {
        $this->userRegisterationService = $userRegisterationService;
    }

    /**
     * User Sign-up
     */
    public function signUp(Request $request)
    {
        $inputs = $request->only('username', 'first_name', 'last_name', 'email', 'password');

        try {

            $user = $this->userRegisterationService->userSignUp($inputs);
            if($user) {
                $data = [
                    'user' => $user
                ];
                return ApiResponse::success($data, 'Registeration successfully.');
            }

            throw new GeneralException('Unable to Register User.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        }  catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal(['message' => 'Some internal error.']);
        }
    }

    /**
     * User Email Verify
     */
    public function emailVerify(Request $request) 
    {

        try {
            
            $isVerified = $this->userRegisterationService->emailVerify($request->only('id', 'code'));

            if($isVerified) {
                return ApiResponse::success([], 'Email verified successfully.');
            }

            throw new GeneralException('Unable to verify your email.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        }  catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal(['message' => 'Some internal error.']);
        }
    }

    public function resendEmailVerifyCode(Request $request) 
    {
        try {

            $status = $this->userRegisterationService->resendEmailVerficationCode($request->only('id'));

            if($status) {    
                return ApiResponse::success([], 'Code sent successfully.');
            }

            throw new GeneralException('Unable to send code.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        }  catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal(['message' => 'Some internal error.']);
        }
    }
}
