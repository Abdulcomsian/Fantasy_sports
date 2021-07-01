<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\League;
use App\Models\Player;
use App\Models\LeagueRound;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class DraftController extends Controller
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
            $playerIds = [];
            if(isset($league->rounds)){
                foreach ($league->rounds->whereNotNull('player_id') as $key => $round) {
                    $playerIds[] = $round->player->id;
                }
            }
            $roundsArr = [];
            $roundNumber = 0; 

            foreach($league->rounds as $index => $round){
                if($round->round_number != $roundNumber){
                    $roundNumber = $round->round_number;
                    $subround = 0;
                }
                $roundsArr[$roundNumber][$subround] = $round;
                $subround++;
            }
            return view('league.draft', [
                'league' => $league, 
                'players' => Player::whereNotIn('id', $playerIds)->get(),
                'last_pick' => leagueRound::fetchData($id, 'desc'),
                'league_rounds' => $roundsArr
            ]);
        }
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function savePick(Request $request, $leagueId){
        $validator = Validator::make($request->all(), [
            'player_id' => 'required'
        ]);

        if (!$validator->fails()) {
            $roundId = 0;
            $leagueRound = [];
            if(!$request->has('round_id') || ($request->has('round_id') && $request->round_id == 0)){
                $leagueRound = leagueRound::fetchData($leagueId);
                $roundId = isset($leagueRound->round_id) ? $leagueRound->round_id : 0;
            }else{
                $roundId = $request->round_id;
            }
            if(isset($roundId) && $roundId > 0){
                LeagueRound::where('id', $roundId)->update(['player_id' => $request->player_id]);
                return $this->sendResponse(200, 'Pick saved successfully.', ['round_id' => $roundId, 'league_round' => $leagueRound, 'counts' => League::getLeagueRoundsCount($leagueId)]);
            }else{
                return $this->sendResponse(400, 'Something went wrong. Please try again later.');    
            }
        }else{
            return $this->sendResponse(400, 'Required fields are missing.');        
        }
    }

    public function deletePick(Request $request, $leagueId, $roundId){

        if (isset($roundId) && intval($roundId) > 0) {
            LeagueRound::where('id', $roundId)->update(['player_id' => null]);
            $leagueRound = leagueRound::fetchData($leagueId,  'desc');
            //Fetch last player pick/round
            return $this->sendResponse(200, 'Pick deleted successfully.', ['league_round' => $leagueRound, 'counts' => League::getLeagueRoundsCount($leagueId)]);
        }else{
            return $this->sendResponse(400, 'Required fields are missing.');         
        }
    }

    public function saveTimer(Request $request, $leagueId){
        $league = League::findOrFail($leagueId);
        $league->timer_value = $request->timer_value;
        $league->remaining_timer = null;
        $league->draft_timer = null;
        $league->save();
        return $this->sendResponse(200, 'Time saved successfully.', ['timer_value' => $league->timer_value]);
    }

    public function timerSettings(Request $request, $leagueId, $type){
        $league = League::findOrFail($leagueId);
        $startTime = '';
        if($type == 'start'){
            $startTime = $league->timer_value;
            $date = Carbon::parse($request->local_date_time);
            if($league->remaining_timer){
                if($league->remaining_timer != '00:00:00'){
                    $startTime = $league->remaining_timer;
                }else{
                    $type = 'restart';   
                }
            }
            $time = explode(':', $startTime);
            
            $date = $date->addHours($time[0])->addMinutes($time[1])->addSeconds($time[2]);
            $league->draft_timer = $date;
        }else if($type == 'stop'){
            $league->draft_timer = null;
            $league->remaining_timer = $request->remaining_timer;
        }else{
            $league->draft_timer = null;
            $league->remaining_timer = null;
            $startTime = $league->timer_value;
        }
        $league->save();
        return $this->sendResponse(200, 'Time saved successfully.', ['timer_type' => $type, 'timer_value' => $startTime]);
    }
}
