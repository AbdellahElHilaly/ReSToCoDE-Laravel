<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'menu_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(FeedBack::class, 'menu_id');
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'menu_id');
    }

}
