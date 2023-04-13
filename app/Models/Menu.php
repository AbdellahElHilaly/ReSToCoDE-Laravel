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

}

//  cmd for make many to many relationship between two Menu and Meal
// php artisan make:migration create_menu_meal_table --create=menu_meal
