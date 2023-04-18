<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'image' => 'http://127.0.0.1:8000/media/images/64357e22b2552.jpg',
            'quantity' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->date,
            'description' => $this->faker->sentence,
            'user_id' => 1,
        ];
    }
}


