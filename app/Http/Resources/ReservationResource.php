<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'menu' => $this->menu->name,
            'date' => $this->date,
        ];
    }

}

