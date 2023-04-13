<?php

namespace Database\Factories;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class MealFactory extends Factory
{
    protected $model = Meal::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'category_id' => $this->faker->numberBetween(1, 5),
            'user_id' => 1,
            'image' => 'http://127.0.0.1:8000/media/images/64357e22b2552.jpg',
            'quantity' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->sentence,
        ];
    }
}


