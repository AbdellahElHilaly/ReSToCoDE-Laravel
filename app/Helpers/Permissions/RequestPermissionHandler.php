<?php

namespace App\Helpers\Permissions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


trait RequestPermissionHandler {

    

    public function getControllerRequsted($request) {
        $controller = $request->route()->getAction();
        $controller = explode('@', $controller['controller']);
        $controller = explode('\\', $controller[0]);
        $controller = end($controller);
        return $controller;
    }

    public function getMethodRequsted($request) {
        $method = $request->route()->getAction();
        $method = explode('@', $method['controller']);
        $method = end($method);
        return $method;
    }



    // filter 'string' after "=" here : "url": "http://127.0.0.1:8000/api/games/category=auth"

    public function getFindByRequested($request) {
        $url = $request->url();
        $fullUrl = $request->fullUrl();

        // check if url has "="
        if (strpos($url, '=') == false) {
            return 'no_find_by';
        }

        $url = explode('=', $fullUrl);

        $findBy = end($url);
        return $findBy;
    }


    public function requestResult($request){
        $data['controller'] = $this->getControllerRequsted($request);
        $data['method'] = $this->getMethodRequsted($request);
        $data['find_by'] = $this->getFindByRequested($request);

        return $data;
    }







}
