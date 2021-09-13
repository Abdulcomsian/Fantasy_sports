<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 

    {
        Player::create([
            'player_id'=>'1', 
            'first_name'=>'Abdul', 
            'last_name'=>'Basit', 
            'position'=>'RR', 
            'team'=>'QB', 
            'opponent'=>'GB', 
            'game'=>'1', 
            'time'=>'1', 
            'salary'=>'11.00', 
            'fppg'=>'1', 
            'injury_status'=>'1', 
            'starting'=>'1'
        ]);
        Player::create([
            'player_id'=>'1', 
            'first_name'=>'Darius', 
            'last_name'=>'Jessup', 
            'position'=>'FR', 
            'team'=>'RB', 
            'opponent'=>'FQ', 
            'game'=>'1', 
            'time'=>'1', 
            'salary'=>'11.00', 
            'fppg'=>'1', 
            'injury_status'=>'1', 
            'starting'=>'1'
        ]);
           
    }
}
