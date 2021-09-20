<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [["player_id"=>"1","active"=>"1","starting"=>"1","first_name"=>"Buffalo Bills","position"=>"DEF","team"=>"BUF","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"2","active"=>"1","starting"=>"1","first_name"=>"Indianapolis Colts","position"=>"DEF","team"=>"IND","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"3","active"=>"1","starting"=>"1","first_name"=>"Miami Dolphins","position"=>"DEF","team"=>"MIA","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"4","active"=>"1","starting"=>"1","first_name"=>"New England Patriots","position"=>"DEF","team"=>"NE","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"5","active"=>"1","starting"=>"1","first_name"=>"New York Jets","position"=>"DEF","team"=>"NYJ","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"6","active"=>"1","starting"=>"1","first_name"=>"Cincinnati Bengals","position"=>"DEF","team"=>"CIN","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"7","active"=>"1","starting"=>"1","first_name"=>"Cleveland Browns","position"=>"DEF","team"=>"CLE","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"8","active"=>"1","starting"=>"1","first_name"=>"Tennessee Titans","position"=>"DEF","team"=>"TEN","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null],["player_id"=>"9","active"=>"1","starting"=>"1","first_name"=>"Jacksonville Jaguars","position"=>"DEF","team"=>"JAC","height"=>null,"weight"=>null,"dob"=>"0000-00-00","college"=>null,"drafted"=>null]];
        foreach($array as $player){
            
            $name = explode(" ", $player['first_name']);
if(count($name)>0){
    $first_name = $name[0];
    if(count($name)>2){
    $last_name=$name[1] . ' ' . $name[2] ;
    }else{
        $last_name=$name[1];
    }
}else{
$first_name = $name;
$last_name="";
}
$player['first_name'] = $first_name;
$player['last_name'] = $last_name;
            Player::create($player);
            
        }
    }
}


// "player_id"=>$player->player_id,
// "active"=>1,
// "starting"=>$player->starting,
// "first_name"=>$first_name,
// "last_name"=>$last_name,
// "position"=>$player->player_id,
// "team"=>$player->player_id,
// "height"=>$player->player_id,
// "weight"=>$player->weight,
// "dob"=>$player->dob,
// "team"=>$player->team,
// "college"=>$player->college,
// "drafted"=>$player->drafted,