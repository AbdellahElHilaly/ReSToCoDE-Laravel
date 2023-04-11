<?php

namespace App\Helpers\Auth;

use GuzzleHttp\Client;
use DeviceDetector\DeviceDetector;

Trait  TokenTrait
{

    public function tokenValidated($token){
        $currentToken = $this->generateToken();

        /*
        this is the token :

            "ip": "127.0.0.1",
            "device": "",
            "platform": "UNK",
            "browser": "Postman Desktop",
            "expires_at": 1679418098,
            "location": {
                "country": "ZZ",
                "region": "",
                "city": ""
            },
            "network": "no data"

         */

        // 1 - check if token is not empty
        if(empty($token)) return false;

        // 2 - check if token is expired

        if($token['expires_at'] < now()) return false;

        // 3 - compare token with current token we will compare array without expires_at

        unset($token['expires_at']);
        unset($currentToken['expires_at']);

        if($token == $currentToken) return true;





    }

    public function generateToken()
    {
        $dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);
        $dd->parse();
        $device = $dd->getDeviceName();
        $platform = $dd->getOs('name');
        $browser = $dd->getClient('name');
        $ip = request()->ip();
        // $ip = '197.230.213.189'; test real ip
        $key = 'at_YSyNBFSsynfsRc9G6Z7nVz5Rd6Y8l';

        $expiresAt =now()->addMinutes(5);

        $client = new Client();
        $url = 'https://geo.ipify.org/api/v2/country,city,vpn?apiKey='.$key.'&ipAddress='.$ip; // rest api service url
        $response = $client->get($url);
        $ipInformations = json_decode($response->getBody(), true);


        if(isset($ipInformations['location'])){
            $location = $ipInformations['location']['country'] . ' - ' . $ipInformations['location']['region'] . ' - ' . $ipInformations['location']['city'];
        }else $location = "no data";

        if(isset($ipInformations['as']))

            $network = $ipInformations['as']['name'] . ' - ' . $ipInformations['as']['route'] . ' - ' . $ipInformations['as']['domain'];


        else $network = "no data";


        $token = [
            'ip' => $ip,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'expires_at' => $expiresAt,
            'location' => $location,
            'network' => $network,
        ];

        return $token;
    }


    public function checkToken($userToken){
        $token_id = $userToken->id;
        $token = $this->generateToken();

        $message = 'ok';

        $baseMessage1 = "you try to login from a new ";
        $baseMessage2 = "you should verify your account code sended to your email";

        if($userToken['ip'] != $token['ip'])
            $message = $baseMessage1 . "ip " . $baseMessage2;

        else if($userToken['device'] != $token['device'])
            $message = $baseMessage1 . "device " . $baseMessage2;
        else if($userToken['platform'] != $token['platform'])
            $message = $baseMessage1 . "platform " . $baseMessage2;
        else if($userToken['browser'] != $token['browser'])
            $message = $baseMessage1 . "browser " . $baseMessage2;
        else if($token['location'] != $userToken['location'])
            $message = $baseMessage1 . "location " . $baseMessage2;
        else if($token['network'] != $userToken['network'])
            $message = $baseMessage1 . "network " . $baseMessage2;
        return $message;
    }

}
