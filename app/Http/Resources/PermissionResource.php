<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'user' => $this->user->name,
            'pcontroller' => $this->pcontroller->name,
            'pmethode' => $this->pmethode->name,
        ];
    }

}
