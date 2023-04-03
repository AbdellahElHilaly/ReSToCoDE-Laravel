<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Rule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            [
                'name' => 'Free Fire',
                'description' => 'Free Fire is the ultimate survival shooter game available on mobile. Each 10-minute game places you on a remote island where you are pit against 49 other players, all seeking survival. Players freely choose their starting point with their parachute, and aim to stay in the safe zone for as long as possible. Drive vehicles to explore the vast map, hide in trenches, or become invisible by proning under grass. Ambush, snipe, survive, there is only one goal: to survive and answer the call of duty.',
                'price' => 0,
                'user_id' => 2,
                'quantity' => 100,
                'category_id' => 1,
            ],
            [
                'name' => 'FIFA 21',
                'description' => 'FIFA 21 is a football simulation video game published by Electronic Arts as part of the FIFA series. It is the 28th installment in the FIFA series, and was released on 9 October 2020 for Microsoft Windows, PlayStation 4, Xbox One, Nintendo Switch, PlayStation 5 and Xbox Series X and Series S. It is the first game in the series to be released on the PlayStation 5 and Xbox Series X and Series S.',
                'price' => 60,
                'user_id' => 3,
                'quantity' => 100,
                'category_id' => 3,
            ],

            [
                'name' => 'Call of Duty: Mobile',
                'description' => 'Call of Duty: Mobile is a free-to-play first-person shooter video game developed by Tencent Games and TiMi Studios and published by Activision. It was released for Android and iOS devices on October 1, 2019. The game is a part of the Call of Duty series and is a mobile version of the 2019 game Call of Duty: Modern Warfare.',
                'price' => 230,
                'user_id' => 2,
                'quantity' => 100,
                'category_id' => 2,
            ],
            [
                'name' => 'Fortnite',
                'description' => 'Fortnite is a free-to-play battle royale game developed and published by Epic Games. The game was released for Microsoft Windows, macOS, PlayStation 4, and Xbox One in July 2017, with ports for iOS, Android, and the Nintendo Switch in 2018. A Nintendo Switch port was released in 2019. The game has been a commercial success, with over 350 million players across all platforms as of March 2020.',
                'price' => 260,
                'user_id' => 2,
                'quantity' => 100,
                'category_id' => 1,
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
