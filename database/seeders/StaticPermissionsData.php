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
                    'name' => 'GameController',
                    'description' => 'this is a game controller the developer can use it to CRUD his games and the users can use it to read the games',
                ],
                [
                    'name' => 'CategoryController',
                    'description' => 'this is a category controller the admin can use it to CRUD his categories and the developer can use it to CRUD his games',
                ],
        ];

        foreach ($pcontrollers as $pcontroller) {
            Pcontroller::create($pcontroller);
        }


        $rules = [
            [
                'name' => 'geust',
                'description' => 'this is a geust rule the geust can use it to read all models',
            ],
            [
                'name' => 'developer',
                'description' => 'this is a developer rule the developer can use it to CRUD his models and read all models',
            ],
            [
                'name' => 'admin',
                'description' => 'this is a admin rule the admin can use it to CRUD all categories and CRUD all permissions',
            ],


        ];

        foreach ($rules as $rule) {
            Rule::create($rule);
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
