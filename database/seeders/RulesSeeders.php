<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RulesSeeders extends Seeder
{
    public function run(): void
    {

        $rules = [
                [
                    'name' => 'geust',
                    'description' => 'this is a geust rule the geust can use it to read all models',
                ],
                [
                    'name' => 'shef',
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

    }
}




