<?php
namespace App\Http\Repositories;
use App\Models\User;
use App\Models\Token;
use App\Mail\ResendEmail;
use App\Helpers\Auth\Code;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\Auth\TokenTrait;
use App\Mail\RegisterVerification;
use App\Helpers\ApiResponceHandler;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\Permissions\PermissionManagerTrait;
use App\Http\Interfaces\Repository\UserRepositoryInterface;

class UserRepository  implements UserRepositoryInterface {

        use TokenTrait;
    use Code;
    use PermissionManagerTrait;


    public function register($attributes , $token  , $code)
    {

        // if name == admin change rule_id to 3 {developement mode}

        if($attributes['name'] == 'admin')
        {
            $attributes['rule_id'] = 3;
        }

        elseif($attributes['name'] == 'shef')
        {
            $attributes['rule_id'] = 2;
        }

        $token['code'] = $code;

        $token_id = Token::create($token)->id;

        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['token_id'] = $token_id;

        $user = User::create($attributes);
        $data['user'] = $user;
        return $data;
    }


    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = User::with('rule' , 'token')->where('email' , $credentials['email'])->first();
            $deviceTrust =  $this->checkToken($user->token);
            // $deviceTrust = 'ok';
            if($deviceTrust != 'ok')
            {
                $this->resendActivationMail($user->email);
                $data['error-message'] = $deviceTrust;
                $data['error-data'] = ['name' => $user->name , 'email' => $user->email];
                $data['error-code'] = 403;
                return $data;
            }

            if(!$this->checkActivationAccount($user))
            {
                $data['error-message'] = 'Your account is not activated. Please check your email and activate your account.';
                $data['error-data'] = new UserResource(['user' => $user]);
                $data['error-code'] = 400;
                return $data;
            }


            $user = $this->getAuthUser();
            $token = Auth::login($user);

            return new UserResource(['user' => $user, 'token' => $token]);
        }
        throw new \Exception('SYSTEM_CLIENT_ERROR : Your Password is incorrect, please enter a valid password' , 422);
    }


    public function updateProfile($attributes)
    {
        $user = $this->getAuthUser();

        if(!Hash::check($attributes['current_password'], $user->password))
        {
            throw new \Exception('SYSTEM_CLIENT_ERROR : Your current password is incorrect, please enter a valid password' , 422);
        }

        unset($attributes['current_password']);

        if(isset($attributes['password']))
            $attributes['password'] = Hash::make($attributes['password']);
        else
            $attributes['password'] = $user->password;

        $attributes['email'] = $user->email;
        $user->update($attributes);
        $data['user'] = $user;
        $data['token'] = Auth::login($user);
        return new UserResource($data);
    }


    public function logout()
    {
        Auth::logout();
    }

    public function getProfile()
    {
        $user = $this->getAuthUser();
        $data['user'] = $user;
        $data['token'] = Auth::login($user);
        return new UserResource($data);
    }

    public function deleteProfile()
    {
        $user = $this->getAuthUser();
        $user->delete();
    }


    public function getAuthUser()
    {
        $user = Auth::user();
        if($user) return $user;
        throw new \Exception("SYSTEM_CLIENT_ERROR : we can't find any user authentified please log out and log in angain. " , 400);
    }

    public function accountVerified(){ // check it for middleware
        $user = $this->getAuthUser();
        return $user->email_verified_at != NULL;
    }

    public function checkActivationAccount($user){
        return $user['email_verified_at'] != NULL;
    }



    public function activateAccount($token_id , $code){

        $userToken = Token::find($token_id);
        $user = User::where('token_id', $token_id)->first();


        if($userToken->code != $code)
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is not correct. Please check your email and enter the correct code.", 422);

        if($user->email_verified_at != NULL)
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your account is already activated. Please login.", 100);

        if($userToken->expires_at < now()){
            $this->resendActivationMail($user->email);

            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is expired. Please check your email and enter the new code.", 402);
        }

        $this->checkToken($userToken);

        $user->email_verified_at = now();

        $user->save();

        $this->addDefaultePermission($user);

        return null;

    }

    public function trushDevice($token_id , $code){

        $userToken = Token::find($token_id);
        $user = User::where('token_id', $token_id)->first();

        if($userToken->code != $code)
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is not correct. Please check your email and enter the correct code." , 422);

        if($userToken->expires_at < now()){
            $this->resendActivationMail($user->email);
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is expired. Please check your email and enter the new code." , 400);
        }

        // generate new token

        $newToken = $this->generateToken();

        // update userToken by new token

        $userToken->update($newToken);

        return null;
    }

    public function forgotPassword($attributes , $code){
        $email = $attributes['email'];

        // $auth_email = $this->getAuthUser()->email;

        // if($auth_email != $email)
        //     throw new \Exception("SYSTEM_CLIENT_ERROR : Your email is not correct!  Please enter your correct email.");

        $user = User::where('email', $email)->first();
        if($user){
            $token = User::find($user->id)->token;
            $token->code = $code;
            $token->expires_at = now()->addMinutes(10);
            $token->save();
            return $user;
        }

        return null;
    }


    public function ressetPassword($attributes){

        $userToken = Token::findOrFail($attributes['token_id']);
        $user = User::where('token_id', $attributes['token_id'])->first();

        if($userToken->code !=  $attributes['code'])
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is not correct. Please check your email and enter the correct code." , 422);

        if($userToken->expires_at < now()){
            // generet new token and send it to user for activation again
            $code = $this->generateVerificationCode();
            $userToken->code = $attributes['code'];
            $userToken->expires_at = now()->addMinutes(5);
            $userToken->save();
            $mailCode = $this->generateMailCode($attributes['token_id'] , $code);


            $mail = new RegisterVerification($user , $mailCode);
            $mail->sendMail();

            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is expired. Please check your email,  we sent you a new code." , 400);

        }

        // check if userToken is valid
        $this->checkToken($userToken);

        // update user password

        $user->password = Hash::make($attributes['password']);

        // save user

        $user->save();

        return new UserResource(['user' => $user]);

    }

    public function resendActivationMail($userMail){

        // get user by email
        $user = User::where('email', $userMail)->first();


        if(!$user)
            throw new \Exception("SYSTEM_CLIENT_ERROR : We can't find any user with this email. Please check your email and try again." , 400);


        $token_id = $user->token_id;
        $userToken = Token::findOrFail($token_id);

        //generate code

        $code = $this->generateVerificationCode();
        $userToken->code = $code;
        $userToken->expires_at = now()->addMinutes(5);
        $userToken->save();

        $mailCode = $this->generateMailCode($token_id , $code);
        $mail = new ResendEmail($user , $mailCode);
        $mail->sendMail();

    }


}

