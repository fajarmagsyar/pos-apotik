<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            [
                'name' => 'Tirza Administrator',
                'email' => 'admin@tirza.com',
                'password' => bcrypt('ateblukup'),
                'foto' => '/img/user.jpg',
                'level' => 1
            ],
            [
                'name' => 'Kasir Baumata',
                'email' => 'kasir_baumata@tirza.com',
                'password' => bcrypt('ikides'),
                'foto' => '/img/user.jpg',
                'level' => 2
            ]
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}
