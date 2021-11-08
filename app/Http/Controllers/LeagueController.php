<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\League;
use App\Models\LeagueTeam;
use App\Models\Player;
use App\Models\KeeperList;
use App\Models\LeaguePermission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\LeagueRound;
use App\Models\Roster;
use App\Models\RosterTeamplayer;
use DB;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('league.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'draft_type' => 'required',
            'league_size' => 'required',
            // 'draft_round' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $league = new League();
            $league->name = $request->name;
            $league->draft_type = $request->draft_type;
            $league->draft_round = $request->draft_round ?? '0';
            $league->joiner_key = strtoupper(md5(uniqid()));
            $league->created_by = $user->id;
            $league->save();

            //work for roster view
            $draft_round = 0;
            for ($i = 0; $i < count($request->posrow); $i++) {
                if ($request->posrow[$i] > 1) {
                    $draft_round += $request->posrow[$i];
                    for ($j = 0; $j < $request->posrow[$i]; $j++) {
                        $roster = new Roster();
                        $roster->league_id = $league->id;
                        $roster->position = $request->pos[$i];
                        $roster->orderno = $request->order[$i];
                        $roster->color = $request->favcolor[$i] ?? '#000';
                        $roster->save();
                    }
                } elseif ($request->posrow[$i] == 1) {
                    $draft_round += $request->posrow[$i];
                    $roster = new Roster();
                    $roster->league_id = $league->id;
                    $roster->position = $request->pos[$i];
                    $roster->orderno = $request->order[$i];
                    $roster->color = $request->favcolor[$i] ?? '#000';
                    $roster->save();
                }
            }
            League::where('id', $league->id)->update(['draft_round' => $draft_round]);
            ///end of roster view

            if (isset($league->id) && $request->league_size) {
                $teams = [];
                for ($size = 0; $size < $request->league_size; $size++) {
                    $teamNumber = $size + 1;
                    $teams[] = [
                        'league_id' => $league->id,
                        'team_name' => 'Team ' . $teamNumber,
                        'team_email' => $user->email,
                        'team_order' => $teamNumber,
                        'created_by' => $user->id,
                    ];
                }
                $league->teams()->createMany($teams);
                $league->users()->attach($user->id, ['permission_type' => 1]);

                /*$permissions = [
                    ['league_id' => $league->id, 'permission_type' => 1, 'created_by' => $user->id],
                    ['league_id' => $league->id, 'permission_type' => 2, 'created_by' => $user->id]
                ];
                $league->permissions()->createMany($permissions); */
                return $this->sendResponse(200, 'League created successfully.', ['id' => $league->id]);
            }
            return $this->sendResponse(400, 'Something went wrong. Please try again later.');
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function assignOrder(Request $request, $id)
    {

        if (isset($id) && intval($id) > 0) {
            $league = League::with(['teams'])->find($id);
            return view('league.assignOrder', ['league_id' => $league->id, 'teams' => $league->teams ?? []]);
        }
        abort(404);
    }

    public function updateOrder(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'teams' => 'required'
        ]);

        if (!$validator->fails()) {
            $user = Auth::user();
            $league = League::findOrFail($leagueId);
            foreach ($request->teams as $key => $team) {
                $dbTeam = LeagueTeam::find($team['team_id']);
                $dbTeam->team_order = $key + 1;
                $dbTeam->updated_by = $user->id;
                $dbTeam->save();
            }
            League::saveLeagueRounds($league, 1, 'delete');
            return $this->sendResponse(200, 'Teams updated successfully.', ['id' => $league->id]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function updateRoundOrder(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'rounds' => 'required'
        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $league = League::findOrFail($leagueId);
            $leagueRounds = json_decode($request->rounds);
            foreach ($leagueRounds as $rounds) {
                foreach ($rounds as $key => $round) {
                    $dbRound = LeagueRound::find($round->round_id);
                    $dbRound->team_id = $round->team_id;
                    $dbRound->round_order = $key + 1;
                    $dbRound->updated_by = $user->id;
                    $dbRound->save();
                }
            }
            return $this->sendResponse(200, 'Rounds updated successfully.', ['id' => $league->id]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
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
        if (isset($id) && intval($id) > 0) {
            League::find($id)->delete();
            return $this->sendResponse(200, 'League deleted successfully.');
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function teams(Request $request, $id)
    {
        if (isset($id) && intval($id) > 0) {
            $league = League::with(['teams', 'permissions'])->find($id);
            return view('league.teams', ['league_id' => $league->id, 'teams' => $league->teams ?? []]);
        }
        abort(404);
    }

    public function updateTeams(Request $request, $id)
    {
        if (isset($id) && intval($id) > 0) {
            $user = Auth::user();
            $league = League::find($id);
            $teams = [];
            foreach ($request->teams as $key => $team) {

                $teams[] = [
                    'league_id' => $league->id,
                    'team_name' => $team['team_name'] ?? 'Team ' . ($key + 1),
                    'team_email' => $team['team_email'] ?? $user->email,
                    'created_by' => $user->id,
                ];
            }
            $league->teams()->delete();
            $league->teams()->createMany($teams);
            return $this->sendResponse(200, 'Data updated successfully.', ['id' => $league->id]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function settings(Request $request, $id)
    {
        if (isset($id) && intval($id) > 0) {
            $league = League::with(['teams', 'users', 'permissions'])->findOrFail($id);
            $leaguser = DB::table('league_user')->select('team_id')
                ->where('league_id', $id)
                ->first();
            $rosterdata = DB::table('rosters')
                ->select('color', 'position', DB::raw('count(id) as totalcount'))
                ->where('league_id', $id)
                ->groupBy('position')
                ->orderBy('orderno')
                ->get();
            return view('league.settings', ['league' => $league, 'leaguser' => $leaguser, 'rosterdata' => $rosterdata]);
        }
        abort(404);
    }

    public function updateSettings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'league_id' => 'required',
            'name' => 'required',
            'draft_round' => 'required',
            'teams' => 'required'
        ]);

        if (!$validator->fails()) {
            $user = Auth::user();
            $league = League::findOrFail($request->league_id);
            $oldDraftRound = intval($league->draft_round);
            $draftRound = intval($request->draft_round);
            $league->name = $request->name;
            $league->draft_round = $draftRound;
            $league->updated_by = $user->id;
            $league->save();
            if ($oldDraftRound != $draftRound) {
                if ($oldDraftRound < $draftRound) {
                    League::saveLeagueRounds($league, $oldDraftRound + 1);
                } elseif ($oldDraftRound > $draftRound) {
                    League::deleteLeagueRounds($league, $draftRound);
                }
            }
            $teamSize = $league->teams()->count();
            foreach ($request->teams as $key => $team) {

                $dbTeam = LeagueTeam::find($team['team_id']);
                $isNewTeam = 0;
                if (!isset($dbTeam->id)) {
                    $isNewTeam = 1;
                    $dbTeam = new LeagueTeam();
                    $dbTeam->league_id = $league->id;
                    $dbTeam->team_order = ++$teamSize;
                }
                $dbTeam->team_name = $team['team_name'] ?? 'Team ' . ($key + 1);
                $dbTeam->team_email = $team['team_email'] ?? $user->email;
                if (!$dbTeam->created_by) {
                    $dbTeam->created_by = $user->id;
                }
                $dbTeam->updated_by = $user->id;
                $dbTeam->save();
                if ($isNewTeam) {
                    League::addNewTeamLeagueRounds($league, $teamSize, $dbTeam->id);
                }
            }
            return $this->sendResponse(200, 'League updated successfully.', ['id' => $league->id]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function deleteTeam(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required'
        ]);

        if (!$validator->fails()) {
            $league = League::find($leagueId);
            $league->rounds()->where('team_id', $request->team_id)->delete();
            $league->teams()->where('id', $request->team_id)->delete();
            return $this->sendResponse(200, 'Team deleted successfully.', ['league' => $league]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function joinLeague(Request $request)
    {

        if (isset($request->key) && !empty($request->key)) {
            $league = League::fetchLeagueInfoByKey($request->key);
            if ($league) {
                return view('league.join', ['league' => $league]);
            }
        }
        abort(404);
    }

    public function joinLeagueTeam(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required',
            'team_name' => 'required'
        ]);

        if (!$validator->fails()) {
            try {
                $league = League::findOrFail($leagueId);
                $user = Auth::user();
                $league->users()->attach($user->id, ['permission_type' => 3, 'team_id' => $request->team_id]);
                $league->teams()->where('id', $request->team_id)->update(['team_name' => $request->team_name, 'team_email' => $user->email]);
                return $this->sendResponse(200, 'League joined successfully.', ['league' => $league]);
            } catch (ModelNotFoundException $exception) {
                return $this->sendResponse(404, 'League not found.');
            }
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function saveCommish(Request $request, $leagueId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required'
        ]);

        if (!$validator->fails()) {
            try {
                $message = $request->type == 1 ? 'Commish' : 'Co commish';
                $league = League::findOrFail($leagueId);
                if ($request->type == 1) {
                    $league->users()->where('user_id', Auth::user()->id)->update(['permission_type' => $request->type, 'team_id' => $request->user_id]);
                } elseif ($request->type == 2) {
                    $league->users()->where('user_id', Auth::user()->id)->update(['permission_type2' => $request->type, 'team_id2' => $request->user_id]);
                }
                return $this->sendResponse(200, $message . ' added successfully.', ['league' => $league]);
            } catch (ModelNotFoundException $exception) {
                return $this->sendResponse(404, 'League not found.');
            }
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function rounds(Request $request, $id)
    {
        if (isset($id) && intval($id) > 0) {
            /*$league = League::with(['rounds.team', 'permissions'])->findOrFail($id);
            //If no round found then create round and fetch all rounds with team from the database
            if(isset($league->rounds) && $league->rounds->count() <= 0){
                League::saveLeagueRounds($league);
                $league = League::with(['rounds.team', 'permissions'])->findOrFail($id);
            }*/
            $league = League::leagueData($id, 'teams');

            //CHECKMATE ADDED BY AWAIS START
            $teams = new LeagueTeam();
            $teams = $teams->where('league_id', $id)->get();
            //CHECKMATE ADDED BY AWAIS END

            return view('league.rounds', ['league' => $league, 'teams' => $teams]);
        }
        abort(404);
    }

    public function changeStatus(Request $request, $leagueId)
    {

        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if (!$validator->fails()) {
            $user = Auth::user();
            $league = League::findOrFail($leagueId);
            $league->status = $request->status;
            $league->updated_by = $user->id;
            $league->save();
            return $this->sendResponse(200, 'League status updated successfully.', ['id' => $league->id]);
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function getteam(Request $request)
    {
        $id = $_GET['id'];
        if (isset($request->myinput3)) {
            // $leaguekeeperplayer = KeeperList::where('league_id', $id)->get();
            // $playerIds = [];
            // foreach ($leaguekeeperplayer as  $round) {
            //     $playerIds[] = $round->player_id;
            // }
            $league = League::leagueData($id);
            $playerIds = [];
            if (isset($league->rounds)) {
                foreach ($league->rounds->whereNotNull('player_id') as $key => $round) {
                    $playerIds[] = $round->player->id;
                }
            }
            $players = Player::whereNotIn('id', $playerIds)->where(
                function ($query) {
                    return $query->where('first_name', 'like',  $_GET['qparmas'] . '%')
                        ->orwhere('last_name', 'like',  $_GET['qparmas'] . '%');
                }
            )
                ->get();
        } else {
            $league = League::leagueData($id);
            $playerIds = [];
            if (isset($league->rounds)) {
                foreach ($league->rounds->whereNotNull('player_id') as $key => $round) {
                    $playerIds[] = $round->player->id;
                }
            }
            $players = Player::whereNotIn('id', $playerIds)->where(
                function ($query) {
                    return $query->where('first_name', 'like',  $_GET['qparmas'] . '%')
                        ->orwhere('last_name', 'like',  $_GET['qparmas'] . '%');
                }
            )
                ->get();
        }
        echo json_encode($players);
    }

    public function save_roster(Request $request, $leagueId)
    {
        for ($i = 0; $i < count($request->posrow); $i++) {
            if ($request->posrow[$i] > 1) {
                for ($j = 0; $j < $request->posrow[$i]; $j++) {
                    $roster = new Roster();
                    $roster->league_id = $leagueId;
                    $roster->position = $request->pos[$i];
                    $roster->color = $request->favcolor[$i];
                    $roster->save();
                }
            } elseif ($request->posrow[$i] == 1) {
                $roster = new Roster();
                $roster->league_id = $leagueId;
                $roster->position = $request->pos[$i];
                $roster->color = $request->favcolor[$i];
                $roster->save();
            }
        }
        return back();
    }

    //insert new row for roster when click on plush button
    public function insertrow(Request $request)
    {
        //add new round when click on plus
        $user = Auth::user();
        $league = League::findOrFail($request->league_id);
        $oldDraftRound = intval($league->draft_round);
        $draftRound = intval($request->draft_round);
        $league->name = $request->name;
        $league->draft_round = $draftRound;
        $league->updated_by = $user->id;
        $league->save();
        if ($oldDraftRound != $draftRound) {
            if ($oldDraftRound < $draftRound) {
                League::saveLeagueRounds($league, $oldDraftRound + 1);
                //insert roster row
                $leagueId = $request->leagueId;
                $pos = $request->pos;
                $color = $request->color;
                $roster = new Roster();
                $roster->league_id = $leagueId;
                $roster->position = $pos;
                $roster->color = $color;
                $roster->orderno = $request->orderno;
                if ($roster->save()) {
                    echo "success";
                } else {
                    echo "error";
                }
            } elseif ($oldDraftRound > $draftRound) {
                League::deleteLeagueRounds($league, $draftRound);
            }
        }
    }

    //delete new row for roster when click on plush button
    public function deleterow(Request $request)
    {
        //delete round when click on minus
        $user = Auth::user();
        $league = League::findOrFail($request->league_id);
        $oldDraftRound = intval($league->draft_round);
        $draftRound = intval($request->draft_round);
        $league->name = $request->name;
        $league->draft_round = $draftRound;
        $league->updated_by = $user->id;
        $league->save();
        if ($oldDraftRound != $draftRound) {
            if ($oldDraftRound < $draftRound) {
                League::saveLeagueRounds($league, $oldDraftRound + 1);
            } elseif ($oldDraftRound > $draftRound) {
                League::deleteLeagueRounds($league, $draftRound, $request->posd);
                //delete roster row
                $leagueId = $request->leagueId;
                $pos = $request->pos;
                $res = Roster::where(['position' => $pos, 'league_id' => $leagueId])->orderBy('id', 'DESC')->limit(1)->delete();
                if ($res) {
                    echo "success";
                } else {
                    echo "error";
                }
            }
        }
    }
    //add color
    public function addcolor(Request $request)
    {
        $res = Roster::where(['position' => $request->pos, 'league_id' => $request->leagueId])->update([
            'color' => $request->color
        ]);
        if ($res) {
            echo "success";
        } else {
            echo "error";
        }
    }

    //delete league
    public function delete_league($id)
    {
        if (isset($id) && intval($id) > 0) {
            $res = League::find($id)->delete();
            if ($res) {
                LeagueRound::where('league_id', $id)->delete();
                LeagueTeam::where('league_id', $id)->delete();
                Roster::where('league_id', $id)->delete();
                RosterTeamplayer::where('league_id', $id)->delete();
                LeaguePermission::where('league_id', $id)->delete();
                KeeperList::where('league_id', $id)->delete();
                return back();
            }
        } else {
            return back();
        }
    }
}
