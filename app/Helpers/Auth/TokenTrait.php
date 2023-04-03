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
        // $ip = '197.230.213.189';
        $key = 'at_ssHlScfmx4QhQEJZVnVwIrLqwgivl';

        $expiresAt =now()->addMinutes(5);

        $client = new Client();
        $url = 'https://geo.ipify.org/api/v2/country,city,vpn?apiKey='.$key.'&ipAddress='.$ip; // rest api service url
        $response = $client->get($url);
        $ipInformations = json_decode($response->getBody(), true);


        if(isset($ipInformations['location'])){
            $location = [
                "country" => $ipInformations['location']['country'],
                "region" => $ipInformations['location']['region'],
                "city" => $ipInformations['location']['city'],
            ];
        }else $location = "no data";

        if(isset($ipInformations['as']))
            $network = [
                "name" => $ipInformations['as']['name'],
                "route" => $ipInformations['as']['route'],
                "domain" => $ipInformations['as']['domain'],
            ];
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



        $token = $this->generateToken();

        if($userToken['ip'] != $token['ip']) return 'ip not match enter your password to active your account';

        if($userToken['device'] != $token['device']) return 'device not match enter your password to active your account';

        if($userToken['platform'] != $token['platform']) return 'platform not match enter your password to active your account';

        if($userToken['browser'] != $token['browser']) return 'browser not match enter your password to active your account';


        $userLocation = unserialize($userToken['location']);
        if($token['location'] != $userLocation) return 'location not match enter your password to active your account';

        $userNetwork = unserialize($userToken['network']);
        if($token['network'] != $userNetwork) return 'network not match enter your password to active your account';




    }

}
