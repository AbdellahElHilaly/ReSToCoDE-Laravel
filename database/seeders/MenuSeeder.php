<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::factory()
            ->count(2)
            ->create();
    }
}


