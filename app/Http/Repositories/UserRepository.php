<?php
namespace App\Http\Repositories;
use App\Models\User;
use App\Models\Token;
use App\Helpers\Auth\Code;
use App\Helpers\Auth\TokenTrait;
use App\Mail\RegisterVerification;
use App\Helpers\ApiResponceHandler;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use App\Http\Interfaces\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository  implements UserRepositoryInterface {

    use TokenTrait;
    use Code;


    public function register($attributes , $token  , $code)
    {
        // store token in database
        // serialize location and network
        $token['location']  = serialize($token['location']);
        $token['network']  = serialize($token['network']);
        // add code to token
        $token['code'] = $code;

        // store token and get last inserted id

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

            // $user = User::where('email' , $credentials['email'])->first();
            // get user woth role

            $user = User::with('rule')->where('email' , $credentials['email'])->first();

            if(!$this->checkActivationAccount($user))
            {
                $data['error-message'] = 'Your account is not activated. Please check your email and activate your account.';
                $data['error-data'] = new UserResource(['user' => $user]);
                return $data;
            }


            $user = $this->getAuthUser();
            $token = Auth::login($user);

            return new UserResource(['user' => $user, 'token' => $token]);
        }
        throw new \Exception('SYSTEM_CLIENT_ERROR : Your Password is incorrect, please enter a valid password');
    }


    public function updateProfile($attributes)
    {
        $attributes['password'] = Hash::make($attributes['password']);
        $user = $this->getAuthUser();
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

    public function myGames()
    {
        $user = Auth::user();
        return $user->games()->get();
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
        throw new \Exception("SYSTEM_CLIENT_ERROR : we can't find any user authentified please log out and log in angain. ");
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

        // check if account is already activated

        if($user->email_verified_at != NULL)
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your account is already activated. Please login.");

        if($userToken->code != $code)
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is not correct. Please check your email and enter the correct code.");

        if($userToken->expires_at < now()){
            // generet new token and send it to user for activation again
            $code = $this->generateVerificationCode();
            $userToken->code = $code;
            $userToken->expires_at = now()->addMinutes(5);
            $userToken->save();
            $mailCode = $this->generateMailCode($token_id , $code);


            $mail = new RegisterVerification($user , $mailCode);
            $mail->sendMail();

            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is expired. Please check your email and enter the new code.");
        }

        // check if userToken is valid
        $this->checkToken($userToken);

        // update user email_verified_at by token_id


        $user->email_verified_at = now();

        // save user and token

        $user->save();
        $userToken->save();


        return null;

    }

    public function forgotPassword($attributes , $code){
        $email = $attributes['email'];
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
            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is not correct. Please check your email and enter the correct code.");

        if($userToken->expires_at < now()){
            // generet new token and send it to user for activation again
            $code = $this->generateVerificationCode();
            $userToken->code = $attributes['code'];
            $userToken->expires_at = now()->addMinutes(5);
            $userToken->save();
            $mailCode = $this->generateMailCode($attributes['token_id'] , $code);


            $mail = new RegisterVerification($user , $mailCode);
            $mail->sendMail();

            throw new \Exception("SYSTEM_CLIENT_ERROR : Your activation code is expired. Please check your email,  we sent you a new code.");

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

        /*

        the correct email is : lufy2024@gmail.com
        but if i enter this email : luFy2024@gmail.com ,  it works and send me a new activation code
        why ??

        */

        if(!$user)
            throw new \Exception("SYSTEM_CLIENT_ERROR : We can't find any user with this email. Please check your email and try again.");


        $token_id = $user->token_id;
        $userToken = Token::findOrFail($token_id);

        //generate code

        $code = $this->generateVerificationCode();
        $userToken->code = $code;
        $userToken->expires_at = now()->addMinutes(5);
        $userToken->save();

        $mailCode = $this->generateMailCode($token_id , $code);
        $mail = new RegisterVerification($user , $mailCode);
        $mail->sendMail();

    }




}
