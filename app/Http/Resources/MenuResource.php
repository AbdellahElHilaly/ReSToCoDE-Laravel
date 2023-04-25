<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;


class MenuResource extends JsonResource
{

    public function toArray($request)
    {
        //  you can use "graph sql" to get what you want from comments

        // get quantity from pivot table


        if (strpos($request->route()->getName(), 'MealMenu') !== false)
            return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'mealsNumber' => $this->meals->count(),
                'quantity' => $this->quantity,
                'date' => Carbon::parse($this->date)->format('Y-m-d'),
            ];


        return [
            'id' => $this->id,
            'name' => $this->name,
            'chef' => $this->user->name,
            'description' => $this->description,
            'mealsNumber' => $this->meals->count(),
            'quantity' => $this->quantity,
            'image' => $this->image,
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'meals' => MealResource::collection($this->meals),
            'comments' => CommentResource::collection($this->comments),
            'feedbacks' => FeedBackResource::collection($this->feedbacks),
        ];
    }

}




