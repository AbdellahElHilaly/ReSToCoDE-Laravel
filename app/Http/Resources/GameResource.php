<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'developer' => $this->user->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'description' => $this->description,
        ];
    }

}
