<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MealSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\FeedBackSeeder;

class DatabaseSeeder extends Seeder
{


    public function run(): void
    {

        $this->call([


            CategorySeeder::class,

            StaticPermissionsData::class,

            UsersTableSeeder::class,


            GamesTableSeeder::class,

            MenuSeeder::class,

            MealSeeder::class,

            FeedBackSeeder::class,

            CommentSeeder::class,

            MealMenuSeeder::class,

        ]);
    }
}


