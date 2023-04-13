<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{

    public function toArray($request)
    {

        if (strpos($request->route()->getName(), 'menus') !== false)
            return [
                'name' => $this->name,
                'category' => $this->category->name,
                'image' => $this->image,
                'description' => $this->description,
            ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'chef' => $this->user->name,
            'image' => $this->image,
            'quantity' => $this->quantity,
            'description' => $this->description,
        ];
    }

}
