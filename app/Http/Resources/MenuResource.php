<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'chef' => $this->user->name,
            'description' => $this->description,
            'date' =>Carbon::parse($this->date)->format('Y-m-d'),
        ];
    }


}
