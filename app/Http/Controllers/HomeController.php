<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\League;
use App\Models\User;
use Auth;
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

    public function accountedit()
    {
        $userdata=User::where('id',Auth::user()->id)->first();
        return view('userprofile', ['userdata' => $userdata]);
    }
    public function accountupdate(Request $request)
    {
        $user=User::find(Auth::user()->id);
        //dump($user);
        if($user)
        {
            $user->name=$request->name;
            $user->email=$request->email;
           // dump($request->all());
            if($request->password)
            {
                //dd($request->password);
                $user->password=Hash::make($request->password);

            }
            //dd($user);
            $user->save();
            return redirect()->back()->with('message',"Profile Updated Successfully!");
        }
    }
}
