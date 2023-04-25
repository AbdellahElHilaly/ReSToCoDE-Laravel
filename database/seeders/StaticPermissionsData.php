<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\Pmethode;
use App\Models\Prequest;
use App\Models\Pcontroller;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class  StaticPermissionsData extends Seeder
{
    public function run(): void
    {
        $pcontrollers = [
                [
                    'name' => 'CategoryController',
                    'description' => 'CategoryController description',
                ],
                [
                    'name' => 'CommentController',
                    'description' => 'CommentController description',
                ],
                [
                    'name' => 'FeedBackController',
                    'description' => 'FeedBackController description',
                ],
                [
                    'name' => 'MealController',
                    'description' => 'MealController description',
                ],
                [
                    'name' => 'MealMenuController',
                    'description' => 'MealMenuController description',
                ],
                [
                    'name' => 'MenuController',
                    'description' => 'MenuController description',
                ],
                [
                    'name' => 'PermissionController',
                    'description' => 'PermissionController description',
                ],
                [
                    'name' => 'ReservationController',
                    'description' => 'ReservationController description',
                ],



            ];

        foreach ($pcontrollers as $pcontroller) {
            Pcontroller::create($pcontroller);
        }












        $pmethodes = [
            [
                'name' => 'index',
                'description' => 'this is a index methode the user can use it to read all models',
            ],
            [
                'name' => 'show',
                'description' => 'this is a show methode the user can use it to read one model',
            ],
            [
                'name' => 'store',
                'description' => 'this is a store methode the user can use it to create a model',
            ],
            [
                'name' => 'update',
                'description' => 'this is a update methode the user can use it to update a model',
            ],
            [
                'name' => 'destroy',
                'description' => 'this is a destroy methode the user can use it to delete a model',
            ],
        ];

        foreach ($pmethodes as $pmethode) {
            Pmethode::create($pmethode);
        }
        
    }

}
