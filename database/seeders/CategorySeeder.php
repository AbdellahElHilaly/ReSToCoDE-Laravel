<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'main-meal',
                'description' => 'block 2 get from shef ,  only for 1 choise'
            ],


            [
                'name' => 'bread',
                'description' => 'block 0 , 1 choise'
            ],
            [
                'name' => 'salad',
                'description' => 'block 1 ,  only for 1 choise'
            ],

            [
                'name' => 'appetizer',
                'description' => 'block 1 ,  only for 1 choise'
            ],

            [
                'name' => 'dessert',
                'description' => 'block 3 ,  2 choise ,  3 choise if dont have water'
            ],

            [
                'name' => 'water',
                'description' => 'block 3 , 1 choise ,  0 choise if 3 choise dessert'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }


    }
}
