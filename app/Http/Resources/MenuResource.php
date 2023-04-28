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
            'statistics' => [
                'comments' => $this->comments->count(),
                'lastComment' => $this->comments->last()->user->name ?? 'No comments',
                'feedbacks' => $this->feedbacks->count(),
                'feedback' => $this->feedback_avg($this->feedbacks),
                'reservations' => $this->reservations->count(),
                'lastFeedback' => $this->feedbacks->last()->user->name ?? 'No feedbacks',
                'commentsAverage' => $this->comments->count() / ($this->reservations->count() == 0 ? 1 : $this->reservations->count()),
                'feedbacksAverage' => $this->feedbacks->count() / ($this->reservations->count() == 0 ? 1 : $this->reservations->count()),
            ],
        ];
    }


    private function feedback_avg($feedbacks)
    {
        if ($feedbacks->count() == 0) return 0;
        $sum = 0;
        foreach ($feedbacks as $feedback) {
            $sum += $feedback->body;
        }
        $sum = $sum / $feedbacks->count();
        return number_format((float)$sum, 2, '.', '');

    }

}




