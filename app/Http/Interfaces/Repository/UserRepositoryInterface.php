<?php

namespace App\Http\Interfaces\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    public function register($attributes, $token  , $code);
    public function login(array $credentials);
    public function logout();
    public function updateProfile($attributes);
    public function getProfile();
    public function deleteProfile();
    public function getAuthUser();
    public function activateAccount($token_id , $code); // for activation
    public function accountVerified(); // for middleware
    public function checkActivationAccount($credentials); // for login
    public function forgotPassword($attributes , $code);
    public function ressetPassword($attributes);
    public function resendActivationMail($userMail);
}
