<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    public function run(): void
    {

        $this->call([


            CategorySeeder::class,

            StaticPermissionsData::class,

            UsersTableSeeder::class,


            GamesTableSeeder::class,





        ]);
    }
}
