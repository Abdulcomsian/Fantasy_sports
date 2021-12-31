<?php

namespace App\Http\Controllers;

use App\Models\KeeperList;
use Illuminate\Http\Request;
use Validator;
use App\Models\League;
use App\Models\LeagueRound;
use App\Models\LeagueTeam;
use App\Models\User;
use App\Models\Roster;
use App\Models\RosterTeamplayer;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leagues = League::with('users')->whereHas('users', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();
        if ($leagues->count() > 0) {
            $data['setup'] = $leagues->where('status', 'setup');
            $data['started'] = $leagues->where('status', 'started');
        } else {
            $data['setup'] = [];
            $data['started'] = [];
        }
        return view('home', ['leagues' => $data]);
    }
    //new home page temporary here
    public function new_index()
    {
        $leagues = League::select('leagues.*')
            ->join('league_rounds', 'leagues.id', '=', 'league_rounds.league_id')
            ->where('league_rounds.player_id', NULL)
            ->groupBy('league_rounds.league_id')
            ->orderBy('leagues.id', 'desc')
            ->paginate(9);
        $activeclass = "active";
        return view('new-home', compact('activeclass', 'leagues'));
    }
    //completed league
    public function completed_league()
    {
        $leagues = League::select('leagues.*')
            ->join('league_rounds', 'leagues.id', '=', 'league_rounds.league_id')
            ->whereNotNull('league_rounds.player_id')
            ->groupBy('league_rounds.league_id')
            ->orderBy('leagues.id', 'desc')
            ->paginate(9);
        $compclass = "active";

        return view('new-home', compact('leagues', 'compclass'));
    }
    //renew league
    public function renew_league()
    {
        $leagues = League::orderBy('id', 'desc')->paginate(9);
        $renewclass = "active";
        return view('new-home', compact('leagues', 'renewclass'));
    }
    //save renew league
    public function renew_league_save(Request $request)
    {

        $league_id = $request->leagueid;
        $laguename = $request->leaguename;
        $leagrecord = League::find($league_id);
        $newLeague =  $leagrecord->replicate();
        $newLeague->name = $laguename;
        if ($newLeague->save()) {
            $leagroundrecord = LeagueRound::where('league_id', $league_id)->get(); //league round duplicate
            foreach ($leagroundrecord as $record) {
                $newrow = $record->replicate();
                $newrow->player_id = $record->player_id;
                $newrow->league_id = $newLeague->id;
                $newrow->save();
            }
            $leagueteamrecord = LeagueTeam::where('league_id', $league_id)->get(); //leagueteam duplicate
            foreach ($leagueteamrecord as $record) {
                $newrow = $record->replicate();
                $newrow->dup_team_id = $record->id;
                $newrow->league_id = $newLeague->id;
                $newrow->save();
            }
            //work for commish and co-commish
            $leagueuser = DB::table('league_user')->where('league_id', $league_id)->first();
            DB::table('league_user')->insert([
                'league_id' => $newLeague->id,
                'user_id' => $leagueuser->user_id,
            ]);
            //Work for roster
            $rosterrecord = Roster::where('league_id', $league_id)->get();
            foreach ($rosterrecord as $record) {
                $newrow = $record->replicate();
                $newrow->league_id = $newLeague->id;
                if($newrow->save())
                {
                  $rosterid=$newrow->id;
                }

                //Work for roster TEM PLAYER
                $rosterrecord = RosterTeamplayer::where('league_id', $league_id)->get();
                foreach ($rosterrecord as $record1) {
                $check=RosterTeamplayer::where(['league_id'=>$league_id,'team_id'=>$record1->team_id,'player_id'=>$record1->player_id,'rosters_id'=>$record->id])->first();
                if($check)
                {
                    $newrow = $record1->replicate();
                    $newrow->league_id = $newLeague->id;
                    $newrow->rosters_id=$rosterid;
                    $newrow->save();
                }
               
               }
            }
            

            toastr()->success('League Duplicated Successfully!');
            return redirect()->back();
        }
    }

    public function fetchLeagueInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'joiner_key' => 'required'
        ]);

        if (!$validator->fails()) {
            $league = League::fetchLeagueInfoByKey($request->joiner_key);
            if (isset($league->id)) {
                return $this->sendResponse(200, 'League data.', ['league' => $league]);
            } else {
                return $this->sendResponse(404, 'League not found.');
            }
        } else {
            return $this->sendResponse(400, 'Required fields are missing.');
        }
    }

    public function accountedit()
    {
        $userdata = User::where('id', Auth::user()->id)->first();
        return view('userprofile', ['userdata' => $userdata]);
    }
    public function accountupdate(Request $request)
    {
        $user = User::find(Auth::user()->id);
        //dump($user);
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            // dump($request->all());
            if ($request->password) {
                //dd($request->password);
                $user->password = Hash::make($request->password);
            }
            //dd($user);
            $user->save();
            return redirect()->back()->with('message', "Profile Updated Successfully!");
        }
    }
}
