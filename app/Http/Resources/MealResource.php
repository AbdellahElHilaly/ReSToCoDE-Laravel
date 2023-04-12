<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'developer' => $this->user->name,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'description' => $this->description,
        ];
    }

}
