<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\Auth\Code;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\ResetPasswordMail;
use App\Helpers\Auth\TokenTrait;
use App\Mail\RegisterVerification;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Auth\LoginRequest;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ActivationRequest;
use App\Http\Requests\Auth\DemandCodeRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RessetPasswordRequest;
use App\Http\Interfaces\Repository\UserRepositoryInterface;

class AuthController extends Controller
{

    use ErorrExeptionsHandler;
    use ApiResponceHandler;
    use TokenTrait;
    use Code;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('auth:api')->except(['register', 'login', 'activateAccount', 'forgotPassword', 'ressetPassword' , 'resendActivationMail' ,'trushDevice']);
        $this->middleware('alredy.auth')->only(['register','login']);
    }


    public function register(RegisterRequest $request)
    {
        try {

            $attributes = $request->validated();

            $token = $this->generateToken();
            $code = $this->generateVerificationCode();


            $result = $this->userRepository->register($attributes , $token , $code);

            $token_id = $result['user']->token_id;


            $mailCode = $this->generateMailCode($token_id , $code);

            $user =  new UserResource($result);

            $mail = new RegisterVerification($attributes , $mailCode);
            $mail->sendMail();


            return $this->apiResponse($user , true, 'Successfully registered , please check your email to verify your account', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();



            $result = $this->userRepository->login($credentials);

            if(isset($result['error-message']))
                return $this->apiResponse($result['error-data'], false, $result['error-message'],  $result['error-code']);

            return $this->apiResponse($result, true, 'Successfully logged in', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function logout()
    {
        try {
            $this->userRepository->logout();
            return $this->apiResponse(null, true, 'User logged out successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function editProfile(UpdateProfileRequest $request)
    {
        try {
            $attributes = $request->validated();
            $result = $this->userRepository->updateProfile($attributes);
            return $this->apiResponse($result, true, 'User profile updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getProfile()
    {
        try {

            $result = $this->userRepository->getProfile();
            return $this->apiResponse($result, true, 'User profile', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function deleteProfile()
    {
        try {

            $this->userRepository->deleteProfile();
            return $this->apiResponse(null, true, 'User profile deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function activateAccount(ActivationRequest $request)
    {
        try {

            $data = $request->validated();

            $token_id = $data['left'];
            $code = $data['right'];

            $this->userRepository->activateAccount($token_id , $code);

            return $this->apiResponse(null, true, 'Account activated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function trushDevice(ActivationRequest $request)
    {
        try {

            $data = $request->validated();

            $token_id = $data['left'];
            $code = $data['right'];

            $this->userRepository->trushDevice($token_id , $code);

            return $this->apiResponse(null, true, 'Device trushed successfully, please login again', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {

                $attributes = $request->validated();
                $code = $this->generateVerificationCode();
                $result = $this->userRepository->forgotPassword($attributes , $code);

                if($result){
                    $token_id = $result->token_id;
                    $mailCode = $this->generateMailCode($token_id , $code);
                    $mail = new ResetPasswordMail($result , $mailCode);
                    $mail->sendMail();
                    $data['name'] = $result->name;
                    $data['email'] = $result->email;
                    return $this->apiResponse($data, true, 'Email sent successfully, please check your email to reset your password', 100);

                }

                return $this->apiResponse(null, false, 'Email not found', Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function ressetPassword(RessetPasswordRequest $request){

        try{

            $data = $request->validated();

            $attributes['password'] = $data['password'];
            $attributes['token_id'] = $data['left'];
            $attributes['code'] = $data['right'];

            $result = $this->userRepository->ressetPassword($attributes);

            return $this->apiResponse($result, true, 'Password reset successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function resendActivationMail(DemandCodeRequest $request){

        try{

            $data = $request->validated();

            $userMail = $data['email'];

            $this->userRepository->resendActivationMail($userMail);

            return $this->apiResponse($userMail, true, 'Verification code resent successfully, please check your email', Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }

    }








}
