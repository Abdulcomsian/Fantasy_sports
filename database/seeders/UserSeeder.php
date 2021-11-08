<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        User::create([
            'name' => 'Darrius',
            'email' => 'theoffseasongm21@gmail.com',
            'email_verified_at' => '2021-07-30',
            'password' => bcrypt('Offseason@321'),
        ]);
        User::create([
            'name' => 'obaid',
            'email' => 'obaidkust@gmail.com',
            'email_verified_at' => '2021-07-30',
            'password' => bcrypt('obaid123'),
        ]);
    }
}
