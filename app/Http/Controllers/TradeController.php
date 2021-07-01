<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\League;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(isset($id) && intval($id) > 0){
            $league = League::leagueData($id);
            // $playerIds = [];
            // if(isset($league->rounds)){
            //     foreach ($league->rounds->whereNotNull('player_id') as $key => $round) {
            //         $playerIds[] = $round->player->id;
            //     }
            // }
            // $roundsArr = [];
            // $roundNumber = 0; 

            // foreach($league->rounds as $index => $round){
            //     if($round->round_number != $roundNumber){
            //         $roundNumber = $round->round_number;
            //         $subround = 0;
            //     }

            //     $roundsArr[$roundNumber][$subround] = $round;
            //     $subround++;
            // }
        
            return view('league.trade', [
                'league' => $league
            ]);
        }
        abort(404);
    }
}
