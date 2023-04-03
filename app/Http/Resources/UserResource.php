<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {

        $data['id'] = $this['user']->id;
        $data['name'] = $this['user']->name;
        $data['email'] = $this['user']->email;
        if(isset($this['user']->rule)){
            $data['role'] = $this['user']->rule->name;
        }
        if(isset($this['token'])){
            $data['token'] = $this['token'];
        }

        return $data;
    }
}


