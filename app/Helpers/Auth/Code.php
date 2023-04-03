<?php

namespace App\Helpers\Auth;

use GuzzleHttp\Client;
use DeviceDetector\DeviceDetector;

Trait  Code
{
    // generate code have 5 numbers

    public function generateVerificationCode()
    {
        $code = rand(10000, 99999);
        return $code;
    }

    public function generateMailCode($token_id , $code){

        $mailCode = $token_id .'-'. $code;

        return $mailCode;




    }



    public function generateTokenId($mailCode){

        $token_id = explode('-' , $mailCode)[0];

        return $token_id;
    }

}
