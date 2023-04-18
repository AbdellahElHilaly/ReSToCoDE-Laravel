<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\Meal;
use App\Models\MenusMeal;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenusMealsFactory extends Factory
{
    protected $model = MenusMeal::class;

    public function definition()
    {
        $menu = Menu::inRandomOrder()->first();
        $meal = Meal::inRandomOrder()->first();

        return [
            'menu_id' => $menu->id,
            'meal_id' => $meal->id,
            'quantity' => $this->faker->numberBetween(1, 5)
        ];
    }
}
