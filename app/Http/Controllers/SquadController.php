<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\League;
use App\Models\LeagueRound;
use App\Models\LeagueKeeper;
use Validator;
use Auth;

class SquadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(isset($id) && intval($id) > 0){
            $league = League::with('teams')->findOrFail($id);
            return view('league.squads', [
                'league' => $league
            ]);
        }
        abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teamPlayers($leagueId, $teamId)
    {
        if(isset($teamId) && intval($teamId) > 0){
            /*$leagueRounds = LeagueRound::with(['team', 'player', 'keeperCost'])->where(['league_id' => $leagueId,  'team_id'=> $teamId])->get();*/
            $league = League::with(['rounds' => function($q) use($teamId, $leagueId){
                $q->with(['team', 'player', 'keeperCost' => function($q) use ($leagueId){
                    $q->where('league_id', $leagueId);
                }])->where('team_id', $teamId);
            }])->withCount('rounds')->findOrFail($leagueId);

            $leagueRounds = $league->rounds;
            $roundsCount = $league->rounds_count;
            $playersHtml = view('league.players', compact('leagueRounds', 'roundsCount'))->render();
            return $this->sendResponse(200, 'Players fetch successfully.', ['players_html' => $playersHtml]);
        }
        return $this->sendResponse(400, 'Team id is required.');
    }

    public function saveKeeperCost(Request $request, $leagueId){

        $validator = Validator::make($request->all(), [
            'team_id' => 'required',
            'player_id' => 'required',
            'round_number' => 'required'
        ]);
        if (!$validator->fails()) {
            if($request->status == 1){
                $keeper = LeagueKeeper::where(['league_id' => $leagueId, 'round_number' => $request->round_number])->first();
                if(isset($keeper->id) && $keeper->id > 0){
                    return $this->sendResponse(400, 'Keeper cost already added.');        
                }
                $keeper = new LeagueKeeper();
                $keeper->league_id = $leagueId;
                $keeper->team_id = $request->team_id;
                $keeper->player_id = $request->player_id;
                $keeper->round_number = $request->round_number;
                $keeper->created_by = Auth::id();
                $keeper->save();
            }else{
                LeagueKeeper::where(['league_id' => $leagueId, 'team_id' => $request->team_id, 'player_id' => $request->player_id])->delete();
            }
            return $this->sendResponse(200, 'Record update successfully.');
        }else{
            return $this->sendResponse(400, 'Required fields are missing.');        
        }
    }
}
