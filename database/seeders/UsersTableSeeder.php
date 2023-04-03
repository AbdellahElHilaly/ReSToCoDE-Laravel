<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{

    public function addPermissionToUser(int $user_id, int $pcontroller_id, array $pmethodes_id){

        $pemissions = [];

        // user_id and rule_id is a one id

        foreach ($pmethodes_id as $pmethode_id) {

            $pemissions[] = [
                'user_id' => $user_id,
                'pcontroller_id' => $pcontroller_id,
                'pmethode_id' => $pmethode_id,
            ];

        }



        Permission::insert($pemissions);

    }

    public function run(): void
    {

        // create a guest user and get his id

        $guest = [
            'name' => 'Sybluse',
            'email' => 'syb@gmail.com',
            'rule_id' => 1,
            'password' => bcrypt('1234567880'),
        ];

        $guest_id = User::create($guest)->id;


        // add permissions to the guest user


        $this->addPermissionToUser($guest_id , 1  , [1 , 2]);








        $developer1 = [
            'name' => 'garena free fire',
            'email' => 'garena@gmail.com',
            'rule_id' => 2,
            'password' => bcrypt('1234567880'),
        ];

        $developer1_id = User::create($developer1)->id;


        $this->addPermissionToUser($developer1_id, 1  , [1 , 2 , 3 , 4 , 5]);

        $developer2 = [
            'name' => 'Konami',
            'email' => 'Konami@gmail.com',
            'rule_id' => 2,
            'password' => bcrypt('1234567880'),
        ];

        $developer2_id = User::create($developer2)->id;

        $this->addPermissionToUser($developer2_id, 1  , [1 , 2 , 3 , 4 , 5]);


        $admin = [
            'name' => 'abdellah',
            'email' => 'abdellah@gmail.com',
            'rule_id' => 3,
            'password' => bcrypt('1234567880'),
        ];

        $admin_id = User::create($admin)->id;

        $this->addPermissionToUser($admin_id,  1  , [1 , 2 , 5]);
        $this->addPermissionToUser($admin_id,  2 , [1 , 2 , 3 , 4 , 5]);

    }
}
