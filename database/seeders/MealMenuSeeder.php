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

        $meals->each(function ($meal) use ($menus) {
            $menus->each(function ($menu) use ($meal) {
                $menu->meals()->attach($meal->id);
            });
        });


    }
}
