<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\HeartStore\Exceptions\GeneralException;
use App\HeartStore\Exceptions\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\HeartStore\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\HeartStore\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        try {
            ['email' => $email, 'password' => $password] = $request->validated();
            $user = User::where('email', $email)->first();

            if(!$user) {
                throw new GeneralException('Please enter valid credentials');
            }

            if(empty($user->email_verified_at)) {
                throw new GeneralException('Please verify your email.');
            }

            if(Hash::check($password, $user->password)) {
                $user->getRoleNames();
                $user['profile'] = $user->profile;
                $data = [
                    'message' => 'User logged in Successfully',
                    'data' => [
                    'user' => $user,
                    'token' => $user->createToken('opayn')->accessToken 
                   ]
                ];
                return response()->json($data);
            }
            
            throw new GeneralException('Please enter valid credentials.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal();
        }
    }

    /**
     * Send Forgot password code
     */

    public function sendForgetPasswordCode(Request $request) 
    {
        try {

            if($this->userService->sendForgetPasswordCode($request->only('email'))) {
                return ApiResponse::success([], 'Code sent successfully.');
            }

            throw new GeneralException('Unable to send the code.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        } catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal();
        }
    }

    /**
     * Reset Password 
     */

    public function resetPassword(Request $request) 
    {
        try {

            if($this->userService->resetPassword($request->only('password', 'confirm_password', 'code'))) {
                return ApiResponse::success([], 'User password has been updated successfully.');
            }

            throw new GeneralException('Unable to update the password.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        } catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal();
        }
    }

        /**
     * Change Password 
     */

    public function changePassword(Request $request) 
    {
        try {
            $request->request->add(['id' => Auth::user()->id]);
            $user = $this->userService->changePassword($request->only('old_password', 'new_password', 'id'));

            if($user) {
                return ApiResponse::success($user, 'User password has been updated successfully.');
            }

            throw new GeneralException('Unable to update the password.');

        } catch(GeneralException $e) {
            return ApiResponse::errorGeneral($e->getMessage());
        } catch(ValidationException $e) {
            return ApiResponse::validation($e->getValidator());
        } catch (\Throwable $e) {
            Log::error($e);
            return ApiResponse::errorInternal();
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request) 
    {
        try {

            $request->user()->tokens->each(function($token, $key) {
                $token->delete();
            });

            return ApiResponse::success([], 'You have been successfully logged out!');

        } catch (\Throwable $e) {

            Log::error($e);
            return ApiResponse::errorInternal();
        }
    }
}
