<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MealMenuSeeder extends Seeder
{

    public function run(): void
    {
        $meals = Meal::all();
        $menus = Menu::all();

        foreach ($meals as $meal) {
            $meal->menus()->attach($menus->random(rand(1, 3))->pluck('id')->toArray());
        }
    }
}
