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
use App\Models\Roster;
use App\Models\RosterTeamplayer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use DB;
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
            $leaguerecord = leagueRound::with('team')->with('player')->where(['league_id' => $id,'roundtype'=> 1])->where('player_id', '!=', Null)->orderBy('id', 'desc')->first();

            //ON THE CLOCK TEAM AND NEXT TEAM WORK HERE
            $ontheclockteam=LeagueRound::with('team')->where('league_id',$id)->whereNull('player_id')->orderby('id','asc')->limit(1)->first();



           $nextteam=LeagueRound::with('team')->where('league_id',$id)->whereNull('player_id')->orderby('id','asc')->limit(2)->get();
            $postioncolor=Roster::where('league_id',$id)->groupBy('position')->get();
            $colors=[];
            foreach($postioncolor as $color)
            {
              $colors[$color->position]=$color->color;
            }
           
            return view('league.draft', [
                'league' => $league,
                'players' => Player::whereNotIn('id', $playerIds)->get(),
                'last_pick' => leagueRound::fetchData($id, 'desc'),
                'league_rounds' => $roundsArr,
                'leaugeid' => $id,
                'leaguerecord' => $leaguerecord,
                'ontheclockteam'=>$ontheclockteam,
                'nextteam'=>$nextteam,
                'colors'=>$colors
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
                    $leaguerounddata = LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order, 'league_id' => $leagueId])->first();
                    //if user is admin or league admin or team assign to that user
                    if (Auth::user()->role != "Admin" && $league->created_by != Auth::user()->id) {
                        $teamassign = $league->permissions[0]->pivot->team_id;
                        $teamnumber = $leaguerounddata->team_id;
                        if ($teamassign != $teamnumber) {
                            return $this->sendResponse(400, 'Please wait for your clock turn');
                        }
                    }
                    LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order, 'league_id' => $leagueId])->update(['player_id' => $request->player_id,'roundtype'=>2]);
                    $mydata = LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order,'league_id'=>$leagueId])->first();
                    $playerposition = Player::where('id', $request->player_id)->first();
                    $roster_row = Roster::where(['position' => $playerposition->position, 'league_id' => $leagueId])->get();
                    $position_id = '';
                    if (count($roster_row) > 1) {
                       
                        foreach ($roster_row as $row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $row->id;
                                break;
                            }
                        }
                        //work for possible combination
                        if (!$position_id) {
                            if ($playerposition->position == "QB") { //qb
                                $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            } elseif ($playerposition->position == "RB") { //rb
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "WR") {
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "TE") {
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        //work for idp
                        if (!$position_id) {
                            if ($playerposition->position == "DL") {
                                $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //IDP
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "LB") {
                                $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //IDP
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "DB") {
                                $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //IDP
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }


                        //end of posssible combination
                        if (!$position_id) {
                            $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                            foreach ($roster_ben_row as $ben_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $ben_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif (count($roster_row) == 1) {
                       
                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $roster_row[0]->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                        if ($rosterteamcount == 0) {
                            $position_id = $roster_row[0]->id;
                        } else {
                            //WORK FOR POSSIBLE COMBINATION
                            if (!$position_id) {
                                if ($playerposition->position == "QB") { //qb
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                } elseif ($playerposition->position == "RB") { //rb
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "WR") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "TE") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            //END OF POSSIBLE COMBINATION
                            //work for idp
                            if (!$position_id) {
                                if ($playerposition->position == "DL") {
                                    $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "LB") {
                                    $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "DB") {
                                    $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            //WORK FOR BENCH
                            if (!$position_id) {
                                $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                                foreach ($roster_ben_row as $ben_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $ben_row->id;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if ($position_id) {
                        $RosterTeamplayer = new RosterTeamplayer();
                        $RosterTeamplayer->team_id = $mydata->team_id;
                        $RosterTeamplayer->player_id = $request->player_id;
                        $RosterTeamplayer->rosters_id = $position_id;
                        $RosterTeamplayer->league_id = $leagueId;
                        $RosterTeamplayer->round_number = $request->round_number;
                        $RosterTeamplayer->save();
                        //check if round is completed
                        $check=LeagueRound::where(['league_id' => $leagueId,'player_id'=>null])->first();
                         $message='Pick saved successfully';
                        if(!$check)
                        {
                           $message='Draft Completed! Lets Get to Work!';
                        }
                        return $this->sendResponse(200,$message, ['nround_id' => $leaguerounddata->id, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                    }
                    else
                    {
                           LeagueRound::where(['round_number' => $request->round_number, 'round_order' => $request->round_order, 'league_id' => $leagueId])->update(['player_id' => null,'roundtype'=> null]);
                          return $this->sendResponse(400, 'No space available on roster.', ['nround_id' => $leaguerounddata->id, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                    }
                   // return $this->sendResponse(200, 'Pick saved successfully.', ['nround_id' => $leaguerounddata->id, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                } else {
                    $league = League::leagueData($leagueId);
                    //if user is admin or league admin or team assign to that user
                    $teamnumber = LeagueRound::where(['id' => $roundId, 'league_id' => $leagueId])->first();
                    if (Auth::user()->role != "Admin" && $league->created_by != Auth::user()->id) {
                        $teamassign = $league->permissions[0]->pivot->team_id;
                        $teamnumber = $teamnumber->team_id;
                        if ($teamassign != $teamnumber) {
                            return $this->sendResponse(400, 'Please wait for your clock turn');
                        }
                    }
                    LeagueRound::where(['id' => $roundId, 'league_id' => $leagueId])->update(['player_id' => $request->player_id,'roundtype'=> 1]);
                    $mydata = LeagueRound::where('id', $roundId)->first();
                    //get player postion
                    $playerposition = Player::where('id', $mydata->player_id)->first();
                    //COUNT ROSTER ROW
                    $roster_row = Roster::where(['position' => $playerposition->position, 'league_id' => $leagueId])->get();
                      $position_id = '';
                    if (count($roster_row) > 1) {
                        //new work here
                      
                        foreach ($roster_row as $row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $row->id;
                                break;
                            }
                        }
                        //work for possible combination
                        if (!$position_id) {
                            if ($playerposition->position == "QB") { //qb
                                $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            } elseif ($playerposition->position == "RB") { //rb
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "WR") {
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            } elseif ($playerposition->position == "TE") {
                                $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                                //
                                if (!$position_id) {
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        //end of posssible combination

                        //for bench work
                        if (!$position_id) {
                            $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                            foreach ($roster_ben_row as $ben_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $ben_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif (count($roster_row) == 1) {
                        //new work here
                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $roster_row[0]->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                        if ($rosterteamcount == 0) {
                            $position_id = $roster_row[0]->id;
                        } else {
                            //work for possible combination
                            if (!$position_id) {
                                if ($playerposition->position == "QB") { //qb
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                } elseif ($playerposition->position == "RB") { //rb
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "WR") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "TE") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            //end of posssible combination

                            //work for bench row
                            if (!$position_id) {
                                $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                                foreach ($roster_ben_row as $ben_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $mydata->team_id])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $ben_row->id;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if ($position_id) {
                        $RosterTeamplayer = new RosterTeamplayer();
                        $RosterTeamplayer->team_id = $mydata->team_id;
                        $RosterTeamplayer->player_id = $mydata->player_id;
                        $RosterTeamplayer->rosters_id = $position_id;
                        $RosterTeamplayer->league_id = $leagueId;
                        $RosterTeamplayer->save();
                        $check=LeagueRound::where(['league_id' => $leagueId,'player_id'=>null])->first();
                         $message='Pick saved successfully';
                        if(!$check)
                        {
                           $message='Draft Completed! Lets Get to Work!';
                        }
                        return $this->sendResponse(200,$message, ['data' => $mydata, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
                    }
                    else{
                         LeagueRound::where(['id' => $roundId, 'league_id' => $leagueId])->update(['player_id' => null,'roundtype'=> null]);
                         return $this->sendResponse(400, 'No space available on roster.');
                    }
                    // return $this->sendResponse(200, 'Pick saved successfully.', ['data' => $mydata, 'round_id' => $roundId, 'league_round' => $leagueRound, 'leagueid' => $leagueId, 'leagueteam' => $league, 'counts' => League::getLeagueRoundsCount($leagueId)]);
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
        $leagueround = League::find($leagueId);
        if ($request->roundId > $leagueround->draft_round) {
            return response()->json(['status' => 'error', 'message' => 'Round number dose not exist']);
        } else {
            $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->where('player_id', NULL)->first();
            if ($leagueroundplayercheck) {
                if ($leagueroundplayercheck->player_id == null) {
                    $leagueroundplayercheck->player_id = $request->playerId;
                    if ($leagueroundplayercheck->save()) {
                        //save player in keeper list as well
                        $KeeperList = new KeeperList();
                        $KeeperList->team_id = $request->teamid;
                        $KeeperList->player_id = $request->playerId;
                        $KeeperList->league_id = $leagueId;
                        $KeeperList->round_number = $request->roundId;
                        $KeeperList->save();
                        //work for roster view here
                        //get player postion
                        $playerposition = Player::where('id', $request->playerId)->first();
                        //COUNT ROSTER ROW
                        $roster_row = Roster::where(['position' => $playerposition->position, 'league_id' => $leagueId])->get();
                        $position_id = '';
                        if (count($roster_row) > 1) {
                            //new work here
                         
                            foreach ($roster_row as $row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $row->id;
                                    break;
                                }
                            }
                            //work for possible combination
                            if (!$position_id) {
                                if ($playerposition->position == "QB") { //qb
                                    $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                } elseif ($playerposition->position == "RB") { //rb
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "WR") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "TE") {
                                    $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                    //
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                            //end of posssible combination

                            //work for idp
                            if (!$position_id) {
                                if ($playerposition->position == "DL") {
                                    $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "LB") {
                                    $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                } elseif ($playerposition->position == "DB") {
                                    $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                                    foreach ($combination_row as $comb_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $comb_row->id;
                                            break;
                                        }
                                    }
                                    //IDP
                                    if (!$position_id) {
                                        $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            //for bench work
                            if (!$position_id) {
                                $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                                foreach ($roster_ben_row as $ben_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $ben_row->id;
                                        break;
                                    }
                                }
                            }
                        } elseif (count($roster_row) == 1) {
                            //new work here
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $roster_row[0]->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $roster_row[0]->id;
                            } else {
                                //work for possible combination
                                if (!$position_id) {
                                    if ($playerposition->position == "QB") { //qb
                                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                    } elseif ($playerposition->position == "RB") { //rb
                                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    } elseif ($playerposition->position == "WR") {
                                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    } elseif ($playerposition->position == "TE") {
                                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                        //
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                //end of posssible combination

                                //work for idp
                                if (!$position_id) {
                                    if ($playerposition->position == "DL") {
                                        $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //IDP
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    } elseif ($playerposition->position == "LB") {
                                        $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //IDP
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    } elseif ($playerposition->position == "DB") {
                                        $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                                        foreach ($combination_row as $comb_row) {
                                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                            if ($rosterteamcount == 0) {
                                                $position_id = $comb_row->id;
                                                break;
                                            }
                                        }
                                        //IDP
                                        if (!$position_id) {
                                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                            foreach ($combination_row as $comb_row) {
                                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                                if ($rosterteamcount == 0) {
                                                    $position_id = $comb_row->id;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }

                                //work for bench
                                if (!$position_id) {
                                    $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                                    foreach ($roster_ben_row as $ben_row) {
                                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $request->teamid])->count();
                                        if ($rosterteamcount == 0) {
                                            $position_id = $ben_row->id;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($position_id) {
                            $RosterTeamplayer = new RosterTeamplayer();
                            $RosterTeamplayer->team_id = $request->teamid;
                            $RosterTeamplayer->player_id = $request->playerId;
                            $RosterTeamplayer->rosters_id = $position_id;
                            $RosterTeamplayer->league_id = $leagueId;
                            $RosterTeamplayer->round_number = $request->roundId;
                            $RosterTeamplayer->save();
                            return response()->json(['status' => 'success', 'message' => '']);
                        }
                        else{
                               $leagueroundplayercheck->player_id=Null;
                                $leagueroundplayercheck->save();
                               return response()->json(['status' => 'error', 'message' => 'No space available on roster']);
                        }
                        //end of roster view work 
                        // return response()->json(['status' => 'success', 'message' => '']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'something went wrong']);
                    }
                } else {
                    return response()->json(['status' => 'exist', 'message' => 'record already exsit']);
                }
            } else {
                return response()->json(['status' => 'exist', 'message' => 'record already exsit']);
            }
        }
    }

    public function updateroundkeeperlist(Request $request, $leagueId)
    {
        $leagueround = League::find($leagueId);
        if ($request->roundId > $leagueround->draft_round) {
            return response()->json(['status' => 'error', 'message' => 'Round number dose not exist']);
        } else {

            if (isset($request->roundorder) && $request->roundorder != "") {
                if ($request->roundId == $request->oldroundunber) {
                    $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->where('round_order', $request->roundorder)->update([
                        'player_id' => $request->playerId
                    ]);
                } else {
                    LeagueRound::where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->where('team_id', $request->teamid)->update([
                        'player_id' => null
                    ]);
                    $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->where('round_order', $request->roundorder)->update([
                        'player_id' => $request->playerId
                    ]);
                }

                if ($leagueroundplayercheck) {
                    //work for keeper list update
                    $record = KeeperList::where('team_id', $request->teamid)->where('player_id', $request->oldplayerid)->where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->first();
                    $record->player_id = $request->playerId;
                    $record->round_number = $request->roundId;
                    $record->save();
                    //work for roster view here
                    $returndata=$this->update_roster_from_keeper($request->playerId, $request->oldplayerid, $request->roundId, $request->oldroundunber, $leagueId, $request->teamid, $leagueroundplayercheck);
                    if($returndata=='false')
                    {
                         $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->where('round_order', $request->roundorder)->update([
                             'player_id' => $request->playerId
                          ]);
                         return response()->json(['status' => 'error', 'message' => 'No space available on roster']);
                    }
                    else{
                         return response()->json(['status' => 'success', 'message' => '']);
                    }
                   
                } else {
                    return response()->json(['status' => 'error', 'message' => 'something went wrong']);
                }
            } else {
                if ($request->roundId == $request->oldroundunber) {
                    $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->update([
                        'player_id' => $request->playerId
                    ]);
                } else {
                    LeagueRound::where('league_id', $leagueId)->where('round_number',  $request->oldroundunber)->where('team_id', $request->teamid)->update([
                        'player_id' => null
                    ]);
                    $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->update([
                        'player_id' => $request->playerId
                    ]);
                }
                if ($leagueroundplayercheck) {

                    //work for keeper list update
                    $record = KeeperList::where('team_id', $request->teamid)->where('player_id', $request->oldplayerid)->where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->first();
                    $record->player_id = $request->playerId;
                    $record->round_number = $request->roundId;
                    $record->save();
                    //work for roster view here
                    $returndata=$this->update_roster_from_keeper($request->playerId, $request->oldplayerid, $request->roundId, $request->oldroundunber, $leagueId, $request->teamid, $leagueroundplayercheck);
                    if($returndata=='false')
                    {
                         $leagueroundplayercheck = LeagueRound::where('league_id', $leagueId)->where('round_number', $request->roundId)->where('team_id', $request->teamid)->update([
                             'player_id' => NULL
                         ]);
                         return response()->json(['status' => 'error', 'message' => 'No space available on roster']);
                    }
                    else{
                         return response()->json(['status' => 'success', 'message' => '']);
                    }
                } else {
                    return response()->json(['status' => 'error', 'message' => 'something went wrong']);
                }
            }
        }
    }
    //save keeperlist
    public function savekeeperlist(Request $request, $leagueId)
    {
        $leagueround = League::find($leagueId);
        if ($request->round_number > $leagueround->draft_round) {
            return response()->json(['status' => 'error', 'message' => 'Round number dose not exist']);
        }else {
            
                    $KeeperList = new KeeperList();
                    $KeeperList->team_id = $request->teamid;
                    $KeeperList->player_id = $request->id;
                    $KeeperList->league_id = $leagueId;
                    $KeeperList->round_number = $request->round_number;
                    if ($KeeperList->save()) {
                        return response()->json(['status' => 'success', 'message' => 'saved successfully!']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'something went wrong']);
                    }
        }
    }
    //remove keeper list
    public function removekeeperlist(Request $request, $leagueId)
    {

        $record = KeeperList::where(['team_id'=>$request->teamid,'player_id'=>$request->id])->where('league_id', $leagueId)->where('round_number', $request->round_number)->delete();
        if ($record) {
            echo "success";
        } else {
            echo "error";
        }
    }
    //update keeper list
    public function updatekeeperlist(Request $request, $leagueId)
    {
        $leagueround = League::find($leagueId);
        if ($request->round_number > $leagueround->draft_round) {
            return response()->json(['status' => 'error', 'message' => 'Round number dose not exist']);
        } else {
            $record = KeeperList::where('team_id', $request->teamid)->where('player_id', $request->oldplayerid)->where('league_id', $leagueId)->where('round_number', $request->oldroundunber)->first();
            $record->player_id = $request->id;
            $record->round_number = $request->round_number;
            if ($record->save()) {
                return response()->json(['status' => 'success', 'message' => '']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'something went wrong']);
            }
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
        $res = LeagueRound::where(['league_id' => $request->league_id, 'team_id' => $request->team_id, 'round_number' => $request->round_id, 'player_id' => $request->player_id])->update(['player_id' => NULL]);
        if ($res) {
            $rosterres = RosterTeamplayer::where(['league_id' => $request->league_id, 'team_id' => $request->team_id, 'player_id' => $request->player_id])->delete();
            if ($rosterres) {
                return $this->sendResponse(200, 'Player Removed Successfully.');
            } else {
                return $this->sendResponse(400, 'Player Removed from draft Successfully but not fom Roster.');
            }
        }
    }

    public function get_round_order(Request $request, $leagueId)
    {
        $get_round_order = LeagueRound::select('round_order')->where('league_id', $leagueId)->where('team_id', $request->teamid)->where('round_number', $request->roundnumber)->get();
        $playerid=LeagueRound::select('player_id')->where('league_id', $leagueId)->where('team_id', $request->teamid)->where('round_number', $request->roundnumber)->get();
        echo json_encode(['playerid'=>$playerid,'get_round_order'=>$get_round_order]);
    }

    //roster view
    //roster view
    public function roster_view($id)
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
            return view('league.roster', [
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

    //update roster veiw from keeper list
    public function update_roster_from_keeper($playerId, $oldplayerid, $round_number, $old_round_number, $leagueId, $teamid, $leagueroundplayercheck)
    {
        $rostertemplayerdata = RosterTeamplayer::where(['league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid, 'round_number' => $old_round_number])->first();
        // if ($round_number == $old_round_number) {
        //     $rostertemplayerdata = RosterTeamplayer::where(['league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid, 'round_number' => $old_round_number])->first();
            if ($rostertemplayerdata) {
                $rostertemplayerdata->player_id = $playerId;
                $rostertemplayerdata->round_number = $round_number;
                $rostertemplayerdata->save();
                return response()->json(['status' => 'success', 'message' => '']);
            
        } else {
            $playerposition = Player::where('id', $playerId)->first();
            $roster_row = Roster::where(['position' => $playerposition->position, 'league_id' => $leagueId])->get();
            RosterTeamplayer::where(['league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->delete();
             $position_id = '';
            if (count($roster_row) > 1) {
                //new work here
               
                foreach ($roster_row as $row) {
                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                    if ($rosterteamcount == 0) {
                        $position_id = $row->id;
                        break;
                    }
                }
                //work for possible combination
                if (!$position_id) {
                    if ($playerposition->position == "QB") { //qb
                        $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                    } elseif ($playerposition->position == "RB") { //rb
                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif ($playerposition->position == "WR") {
                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif ($playerposition->position == "TE") {
                        $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                        //
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    }
                }
                //end of posssible combination

                //work for idp
                if (!$position_id) {
                    if ($playerposition->position == "DL") {
                        $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //IDP
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif ($playerposition->position == "LB") {
                        $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //IDP
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    } elseif ($playerposition->position == "DB") {
                        $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                        foreach ($combination_row as $comb_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $comb_row->id;
                                break;
                            }
                        }
                        //IDP
                        if (!$position_id) {
                            $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        }
                    }
                }
                //for bench work
                if (!$position_id) {
                    $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                    foreach ($roster_ben_row as $ben_row) {
                        $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $playerId])->count();
                        if ($rosterteamcount == 0) {
                            $position_id = $ben_row->id;
                            break;
                        }
                    }
                }
            } elseif (count($roster_row) == 1) {
                //new work here
                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $roster_row[0]->id, 'league_id' => $leagueId, 'team_id' =>  $teamid, 'player_id' => $oldplayerid])->count();
                if ($rosterteamcount == 0) {
                    $position_id = $roster_row[0]->id;
                } else {
                    //work for possible combination
                    if (!$position_id) {
                        if ($playerposition->position == "QB") { //qb
                            $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                        } elseif ($playerposition->position == "RB") { //rb
                            $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        } elseif ($playerposition->position == "WR") {
                            $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'WR/RB', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        } elseif ($playerposition->position == "TE") {
                            $combination_row = Roster::where(['position' => 'WRT', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'WR/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                            //
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'QB/WR/RB/TE', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    //end of posssible combination

                    //work for idp
                    if (!$position_id) {
                        if ($playerposition->position == "DL") {
                            $combination_row = Roster::where(['position' => 'DL', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //IDP
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        } elseif ($playerposition->position == "LB") {
                            $combination_row = Roster::where(['position' => 'LB', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //IDP
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        } elseif ($playerposition->position == "DB") {
                            $combination_row = Roster::where(['position' => 'DB', 'league_id' => $leagueId])->get();
                            foreach ($combination_row as $comb_row) {
                                $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                if ($rosterteamcount == 0) {
                                    $position_id = $comb_row->id;
                                    break;
                                }
                            }
                            //IDP
                            if (!$position_id) {
                                $combination_row = Roster::where(['position' => 'IDP', 'league_id' => $leagueId])->get();
                                foreach ($combination_row as $comb_row) {
                                    $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $comb_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                                    if ($rosterteamcount == 0) {
                                        $position_id = $comb_row->id;
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    //work for bench
                    if (!$position_id) {
                        $roster_ben_row = Roster::where(['position' => 'BENCH', 'league_id' => $leagueId])->get();
                        foreach ($roster_ben_row as $ben_row) {
                            $rosterteamcount = RosterTeamplayer::where(['rosters_id' => $ben_row->id, 'league_id' => $leagueId, 'team_id' => $teamid, 'player_id' => $oldplayerid])->count();
                            if ($rosterteamcount == 0) {
                                $position_id = $ben_row->id;
                                break;
                            }
                        }
                    }
                }
            }
            if ($position_id) {
                $RosterTeamplayer = new RosterTeamplayer();
                $RosterTeamplayer->team_id = $teamid;
                $RosterTeamplayer->player_id = $playerId;
                $RosterTeamplayer->rosters_id = $position_id;
                $RosterTeamplayer->league_id = $leagueId;
                if ($RosterTeamplayer->save()) {
                    return response()->json(['status' => 'success', 'message' => '']);
                }
            }
            else{
                  return 'false';
            }
        }
        //end for roster veiw here
    }
}
