<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
        [ 'name' => 'salad + fish',                'description' => 'sald morrocan + fish',                'date' => '2023-04-17',            ],
        [ 'name' => 'salad + chicken',                'description' => 'sald morrocan + chicken',                'date' => '2023-04-17',            ],
        [ 'name' => 'pasta + beef',                'description' => 'pasta with beef sauce',                'date' => '2023-04-18',            ],
        [ 'name' => 'pizza + coke',                'description' => 'italian pizza + coke',                'date' => '2023-04-18',            ],
        [ 'name' => 'soup + beef',                'description' => 'morrocan soup + beef',                'date' => '2023-04-19',            ],
        [ 'name' => 'tagine + chicken',                'description' => 'morrocan tagine + chicken',                'date' => '2023-04-19',            ],
        [ 'name' => 'couscous + vegetables',                'description' => 'morrocan couscous + vegetables',                'date' => '2023-04-20',            ],
        [ 'name' => 'grilled meat + rice',                'description' => 'grilled meat with rice',                'date' => '2023-04-20',            ],
        [ 'name' => 'salad + tuna',                'description' => 'salad with tuna',                'date' => '2023-04-20',            ],
        [ 'name' => 'grilled chicken + potatoes',                'description' => 'grilled chicken with potatoes',                'date' => '2023-04-20',            ],
        [ 'name' => 'soup + lamb',                'description' => 'morrocan soup + lamb',                'date' => '2023-04-21',            ],
        [ 'name' => 'tagine + lamb',                'description' => 'morrocan tagine + lamb',                'date' => '2023-04-21',            ],
    ];
        foreach ($menus as $menu) {
            $menu['user_id'] = 1;
            Menu::create($menu);
        }
    }
}

//  cmd for crate comment factory
// php artisan make:factory CommentFactory --model=Comment
