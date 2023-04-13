<?php

namespace Database\Seeders;

use App\Models\FeedBack;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FeedBackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeedBack::factory()
            ->count(36)
            ->create();
    }
}
