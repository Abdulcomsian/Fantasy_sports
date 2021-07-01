<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\League;
use Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leagues = League::with('users')->whereHas('users', function($q){
                        $q->where('users.id', Auth::id());
                    })->get();
        if($leagues->count() > 0){
            $data['setup'] = $leagues->where('status', 'setup');
            $data['started'] = $leagues->where('status', 'started');
        }else{
            $data['setup'] = [];
            $data['started'] = [];
        }
        return view('home', ['leagues' => $data]);
    }

    public function fetchLeagueInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'joiner_key' => 'required'
        ]);

        if (!$validator->fails()) {
            $league = League::fetchLeagueInfoByKey($request->joiner_key);
            if(isset($league->id)){
                return $this->sendResponse(200, 'League data.', ['league' => $league]);    
            }else{
                return $this->sendResponse(404, 'League not found.');    
            }
        }else{
            return $this->sendResponse(400, 'Required fields are missing.');  
        }
    }
}
