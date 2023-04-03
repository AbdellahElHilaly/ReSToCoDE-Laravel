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
                'name' => 'Battle Royale',
                'description' => 'Battle Royale games are a popular genre of video games that involve a large number of players fighting each other to be the last one standing. These games typically involve scavenging for weapons and other resources, building structures for defense, and engaging in combat against other players. The game world gradually shrinks as the game progresses, forcing players into closer proximity and increasing the likelihood of conflict.'
            ],
            [
                'name' => 'Shoter',
                'description' => ' the shooter genre is a subgenre of action games, which focuses on gun and other weapon-based combat'
            ],
            [
                'name' => 'Sport',
                'description' => 'Sport games are video games that simulate the practice of sports. Most sports have been recreated with a game, including team sports, track and field, extreme sports, and combat sports'
            ],
            [
                'name' => 'RPG',
                'description' => 'Role-playing video games (RPGs) are a video game genre where the player controls the actions of a character (and/or several party members) immersed in some well-defined world'
            ],
            [
                'name' => 'Racing',
                'description' => 'Racing games are video games in which the player partakes in a racing competition with any type of land, air, or sea vehicles'
            ],
            [
                'name' => 'Strategy',
                'description' => 'Strategy video games are a video game genre that emphasizes skillful thinking and planning to achieve victory'
            ],
            [
                'name' => 'Adventure',
                'description' => 'Adventure games are a video game genre that involves a player assuming the role of protagonist in an interactive story driven by exploration and puzzle-solving'
            ],
            [
                'name' => 'Action',
                'description' => 'Action games are a video game genre that emphasizes physical challenges, including hand–eye coordination and reaction-time'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }


    }
}
