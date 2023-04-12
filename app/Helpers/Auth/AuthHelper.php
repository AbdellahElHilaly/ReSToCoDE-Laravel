<?php

namespace App\Helpers\Auth;

use GuzzleHttp\Client;
use DeviceDetector\DeviceDetector;

Trait  AuthHelper
{


    public function getAuthUser(){
        $auth = auth()->user();
        if($auth) return $auth;
        throw new \Exception("SYSTEM_CLIENT_ERROR : we can't find any user authentified please log out and log in angain. " , 400);
    }
}
