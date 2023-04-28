<?php

namespace App\Helpers\Auth;

use GuzzleHttp\Client;
use DeviceDetector\DeviceDetector;

Trait  TokenTrait
{

    public function tokenValidated($token){
        $currentToken = $this->generateToken();

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
        // $ip = request()->ip();
        $ip = '197.230.213.189';

        $location = $this->getLocationINfo($ip);
        // $network = $this->getNetworkInfo($ip);
        $network = 'ASMedi - 197.230.192.0/18 - orange.ma - MEDITELECOM';

        $expiresAt = now()->addMinutes(10);

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

    private function getLocationINfo($ip){

        $url = 'http://ip-api.com/json/'.$ip;

        $client = new Client();
        $response = $client->get($url);
        $response = json_decode($response->getBody(), true);
        if(!isset($response['timezone']))  return 'local host inknown location';
        $location = explode('/',$response['timezone'])[0] . ' - ' . $response['country'] . ' - ' . $response['regionName'] . ' - ' . $response['city'];
        return $location;
    }

    private function getNetworkInfo($ip){
        $key = 'at_5L0MCLJR3emxWqlgC84tWwJyWXYnw';
        $url = 'https://geo.ipify.org/api/v2/country,city,vpn?apiKey='.$key.'&ipAddress='.$ip;
        $client = new Client();
        $response = $client->get($url);
        $response = json_decode($response->getBody(), true);
        if(!isset($response['as']))  return 'local host inknown network';
        $network = $response['as']['name'] . ' - ' . $response['as']['route'] . ' - ' . $response['as']['domain'] . ' - ' . $response['isp'];
        return $network;
    }

}
