<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{

    public function toArray($request){

        if (strpos($request->route()->getName(), 'menus') !== false)
            return [
                'id' => $this->id,
                'user' => $this->user->name,
                'body' => $this->body,
                'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            ];

        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'body' => $this->body,
            'menu-name' => $this->menu->name,
            'menu-date' => Carbon::parse($this->menu->date)->format('Y-m-d'),
            'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }

}
