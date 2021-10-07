<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\League;
use App\Models\Player;
use App\Models\LeagueRound;
use App\Models\KeeperList;
use App\Models\LeagueTeam;
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

        if (isset($id) && intval($id) > 0) {
            $league = League::leagueData($id);
            $playerIds = [];
            if (isset($league->rounds)) {
                foreach ($league->rounds->whereNotNull('player_id') as $key => $round) {
                    $playerIds[] = $round->player->id;
                }
            }
            $roundsArr = [];
            $roundNumber = 0;

            foreach ($league->rounds as $index => $round) {
                if ($round->round_number != $roundNumber) {
                    $roundNumber = $round->round_number;
                    $subround = 0;
                }
                $roundsArr[$roundNumber][$subround] = $round;
                $subround++;
            }
            $leaguerecord = leagueRound::where(['league_id' => $id])->where('player_id', '!=', Null)->orderBy('id', 'DESC')->first();
            //dd($leaguerecord);
            return view('league.draft', [
                'league' => $league,
                'players' => Player::whereNotIn('id', $playerIds)->get(),
                'last_pick' => leagueRound::fetchData($id, 'desc'),
                'league_rounds' => $roundsArr,
                'leaugeid' => $id,
                'leaguerecord' => $leaguerecord,
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

    public function savePick(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required'
        ]);

        if (!$validator->fails()) {
            $roundId = 0;
            $leagueRound = [];
            if (!$request->has('round_id') || ($request->has('round_id') && $request->round_id == 0)) {
                $leagueRound = leagueRound::fetchData($leagueId);
                $roundId = isset($leagueRound->round_id) ? $leagueRound->round_id : 0;
            } else {
                $roundId = $request->round_id;
            }
            if (isset($roundId) && $roundId > 0) {
                if ($request->round_order) {
                    $league = League::leagueData($leagueId);
                    $leaguerounddata = LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order])->first();
                    LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order])->update(['player_id' => $request->player_id]);

                    return $this->sendResponse(200, 'Pick saved successfully.', ['nround_id' => $leaguerounddata->id, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                } else {
                    $league = League::leagueData($leagueId);
                    LeagueRound::where('id', $roundId)->update(['player_id' => $request->player_id]);
                    return $this->sendResponse(200, 'Pick saved successfully.', ['round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                }
            } else {
                return $this->sendResponse(400, 'Something went wrong. Please try again later.');
            }
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function saveroundkeeperlist(Request $request, $leagueId)
    {
        $leagueroundplayercheck = LeagueRound::where('round_number', $request->roundId)->where('team_id', $request->teamid)->first();
        if ($leagueroundplayercheck->player_id == null) {
            $res = LeagueRound::where('round_number', $request->roundId)->where('team_id', $request->teamid)->update(['player_id' => $request->playerId]);
            if ($res) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "exist";
        }
    }

    //save keeperlist
    public function savekeeperlist(Request $request, $leagueId)
    {
        // check if record alreay exist on that round against tem
        $record = KeeperList::where('team_id', $request->teamid)->where('league_id', $leagueId)->where('round_number', $request->round_number)->first();
        if ($record == null) {
            $KeeperList = new KeeperList();
            $KeeperList->team_id = $request->teamid;
            $KeeperList->player_id = $request->id;
            $KeeperList->league_id = $leagueId;
            $KeeperList->round_number = $request->round_number;
            if ($KeeperList->save()) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "exist";
        }
    }
    //remove keeper list
    public function removekeeperlist(Request $request,$leagueId)
    {
        $record = KeeperList::where('team_id', $request->teamid)->where('league_id', $leagueId)->where('round_number', $request->round_number)->delete();
        if($record)
        {
            echo "success";
        } else {
            echo "error";
        }
    }
    //update keeper list
    public function updatekeeperlist(Request $request, $leagueId)
    {
        $record = KeeperList::where('team_id', $request->teamid)->where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->first();
        $record->player_id = $request->id;
        $record->round_number = $request->round_number;
        if ($record->save()) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function movekeeperlist(Request $request, $leagueId)
    {
        $record = KeeperList::where('team_id', $request->oldteamid)->where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->first();
        $record->team_id = $request->newteamid;
        if ($record->save()) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function deletePick(Request $request, $leagueId, $roundId)
    {

        if (isset($roundId) && intval($roundId) > 0) {
            LeagueRound::where('id', $roundId)->update(['player_id' => null]);
            $leagueRound = leagueRound::fetchData($leagueId,  'desc');
            //Fetch last player pick/round
            return $this->sendResponse(200, 'Pick deleted successfully.', ['league_round' => $leagueRound, 'counts' => League::getLeagueRoundsCount($leagueId)]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function saveTimer(Request $request, $leagueId)
    {
        $league = League::findOrFail($leagueId);
        $league->timer_value = $request->timer_value;
        $league->remaining_timer = null;
        $league->draft_timer = null;
        $league->save();
        return $this->sendResponse(200, 'Time saved successfully.', ['timer_value' => $league->timer_value]);
    }

    public function timerSettings(Request $request, $leagueId, $type)
    {
        $league = League::findOrFail($leagueId);
        $startTime = '';
        if ($type == 'start') {
            $startTime = $league->timer_value;
            $date = Carbon::parse($request->local_date_time);
            if ($league->remaining_timer) {
                if ($league->remaining_timer != '00:00:00') {
                    $startTime = $league->remaining_timer;
                } else {
                    $type = 'restart';
                }
            }
            $time = explode(':', $startTime);

            $date = $date->addHours($time[0])->addMinutes($time[1])->addSeconds($time[2]);
            $league->draft_timer = $date;
        } else if ($type == 'stop') {
            $league->draft_timer = null;
            $league->remaining_timer = $request->remaining_timer;
        } else {
            $league->draft_timer = null;
            $league->remaining_timer = null;
            $startTime = $league->timer_value;
        }
        $league->save();
        return $this->sendResponse(200, 'Time saved successfully.', ['timer_type' => $type, 'timer_value' => $startTime]);
    }

    //change team function
    public function changeTeam(Request $request)
    {
        $data = explode("|", $request->teamdata);
        $team_id = $data[0];
        $round_id = $data[1];
        $league_id = $data[2];
        $round_order = $data[3];
        //get old team id
        LeagueRound::where(['league_id' => $league_id, 'round_number' => $round_id, 'round_order' => $round_order])->update(['team_id' => $team_id]);
        return $this->sendResponse(200, 'Team saved successfully.');
    }

    public function removePlayer(Request $request)
    {
        // return $request->round_id;
        // $data=explode("|",$request->teamdata);
        // $team_id=$data[0];
        // $round_id=$data[1];
        // $league_id=$data[2];
        // $player_id=$data[3];
        LeagueRound::where(['league_id' => $request->league_id, 'team_id' => $request->team_id, 'round_number' => $request->round_id, 'player_id' => $request->player_id])->update(['player_id' => NULL]);
        return $this->sendResponse(200, 'Player Removed Successfully.');
    }
}
