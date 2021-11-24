@extends('layouts.default')
@section('title', 'City Chart')
@section('css')
<style type="text/css">
.dropdown a:hover{
  background:#000 !important;;
  color:#fff !important;
}
  .circle {
    /*background-color: rgba(255, 82, 82, 1);
    border-radius: 50%;*/

    animation: pulse-red 2s infinite;

  }


  @keyframes pulse-red {
    0% {
      transform: scale(0.9);
      box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7);
    }

    70% {
      transform: scale(1);
      box-shadow: 0 0 0 10px rgba(255, 82, 82, 0);
    }

    100% {
      transform: scale(0.9);
      box-shadow: 0 0 0 0 rgba(255, 82, 82, 0);
    }
  }
  .mybutton{
    border:1px solid white;
    background: linear-gradient(to right, rgba(255,0,0,0), rgba(0,255,255,1));
  }
 .assign_order h2:hover{
    background: red;
  }
  .swal-modal{
    height: 210px;
  }
</style>
@endsection
@section('content')
@php
function compare1($a, $b)
{
// dd($a->round_order);
return (($a->round_order) > ($b->round_order));
}
function compare2($a, $b)
{
// dd($a->round_order);
return (($a->round_order) < ($b->round_order));
  }
  @endphp

  <div class="fixedBanner" style="background:#000 !important;">
    <div class="overlay"></div>
    <audio id="timerBeep">
      <source src="{{ asset('beeps/beep.mp3') }}" />
      <source src="{{ asset('beeps/beep.wav') }}" />
    </audio>
    <audio id="playerBeep">
      <source src="{{ asset('beeps/playerBeep.mp3') }}" />
      <source src="{{ asset('beeps/playerBeep.wav') }}" />
    </audio>
    <div class="container-fluid create_league_table assign_order the_lottery traders city_charts" style="padding-top:35px;">
      <div class="row">
      <div class="col-md-1"></div>
      <div class="col-md-6">
        <nav class="navbar navbar-expand-lg " style="background-color:#000;">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
              <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link active" style="color:#fff" href="{{url('/league/'.$league->id.'/draft')}}">Draft Board</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=keeperlist')}}">Keeper List</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="color:#fff" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Views
                    </a>
                    <div class="dropdown-menu" style="background-color:#000 !important;" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item"  style="color:#fff" href="{{url('/league/'.$league->id.'/roster-view')}}">Rosters</a>
                      <a class="dropdown-item" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=pickview')}}">Pick</a>
                      <a class="dropdown-item" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=collapseview')}}">Collapse</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#fff" href="#">League Notes </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#fff" href="#">GM Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" style="color:#fff" href="#">Chat</a>
                </li>
                </ul>
              </div>
            </nav>
        </div>
<div class="col-md-2">
          <form id="updateLeague" class="draftFrom">
            <div class="list_edit" style="width:100% !important;">
              <div class="row">
                <!-- <div class="col-md-4">
                </div> -->
                <!-- <div class="col-md-12"> -->
                  <div class="custom-control custom-switch d-flex" >                 
                    <button style="margin-right: 5px;" class="btn btn-success mybutton @if($league->status=='keeper') green @else black @endif" data-mode="keeper">Edit Mode</button>
                    <button class="btn btn-success mybutton @if($league->status=='started') green @else black @endif "   data-mode="started">@if($league->status=="keeper"){{'Live Draft Mode'}}@else{{'Live Draft Mode'}}@endif</button>
                  </div>
                <!-- </div> -->
              </div>
            </div>
            <div class="list_edit d-none">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4 no-bdr">
                  <h4><span><i class="fa fa-star yellow"></i>Edit Mode</span></h4>
                </div>

                <div class="col-md-3">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input lequeMode2" id="keeperMode" {{ $league->status == 'keeper' ? 'checked' : '' }} value="keeper">
                    <label class="custom-control-label on-off" for="keeperMode"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="list_edit d-none">
              <div class="row">
                <div class="col-md-4 no-bdr">
                  <h4><span><i class="fa fa-star yellow"></i>Draft Mode</span></h4>
                </div>
                <div class="col-md-4">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input lequeMode2" id="draftMode" {{ $league->status == 'started' ? 'checked' : '' }} value="started">
                    <label class="custom-control-label on-off" for="draftMode"></label>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- <div class="col-md-3">
          <h2 style="width:70%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft</a></h2>
        </div> -->
        
        @if(auth::user()->role=="Admin")
          <div class="col-lg-2 text-right">
            <div class="d-flex" style="justify-content:flex-end;">
              <p style="margin: 0px 30px;" type="button" id="zoom-out"><img style="width:40px;" src="{{ asset('images/plus.png') }}" /></p>
              <p style="" type="button" id="zoom-in"><img style="width:40px;" src="{{ asset('images/minus.png') }}" /></p>
            </div>
          </div>
        @endif

        @if(auth::user()->role=="Admin" || $league->created_by==\Auth::user()->id)
        <div class="col-md-1">
          <p style="width:70%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}"><img style="width:40px;" src="{{ asset('images/setting.png') }}" /></a></p>
        </div>
        @else
            @php
            $dta=\DB::table('league_user')->where(['league_id'=>$league->id,'user_id'=>\Auth::user()->id])->first();
            @endphp
            @if($dta->permission_type==1 || $dta->permission_type==2)
            <div class="col-md-3">
              <h2 style="width:70%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
            </div>
            @endif
        @endif
        <!-- <div class="col-md-1">
      <h2 style="width:100%" type="button" id="zoom-in">+</h2>
      </div>
      <div class="col-md-1">
      <h2 style="width:100%" type="button" id="zoom-out">-</h2>
      </div> -->



      </div>
      <input type="hidden" name="league_id" value="{{ $league->id }}">
      <!-- <div class="top_draft">
    <div class="container">
      <ul class="list-unstyled list-inline">
        <li class="list-inline-item">
          <a href="{{ url('/league/'.$league->id.'/draft') }}" class="draftStatus" data-type="{{ $league->status }}">
            <button class="style-btn">
              <img src="{{ asset('images/min_tab.jpg') }}" title="Switch to {{ $league->status == 'keeper' ? 'Draft Board' : 'Keeper Mode' }}">
            </button>
          </a>
        </li>
        <li class="list-inline-item">
          <a href="{{ url('/league/'.$league->id.'/settings') }}">
            <button class="style-btn">
              <img src="{{ asset('images/user.png') }}">
            </button>
          </a>
        </li>
      </ul>
    </div>
  </div> -->



    </div>
    <div class="league-div">
      <!-- <div class="container-fluid assign_order"> -->
        <!-- <div class="row"> -->
          <!-- <div class="col-lg-6"> -->
   

            <!-- <div class="d-flex">
              <h2 class=" " style="width:20%;"><a style="color:#fff;" href="{{url('/league/'.$league->id.'/draft')}}">Draft Board</a></h2>
             
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=keeperlist')}}">Keeper List</a></h2>
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=collapseview')}}">Collapse View</a></h2>

              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=pickview')}}">Pick View</a></h2>
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="#">League Notes</a></h2>
             
            
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="#">GM Dashboard</a></h2>

              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/roster-view')}}">Roster View</a></h2>
              <h2 class=" " style="width:20%;padding: 14px 33px;"><a style="color:#fff" href="#">Chat</a></h2>
            
            </div> -->
          <!-- </div> -->
      
        <!-- </div> -->
      <!-- </div> -->
      <div class="container ">
        <!-- {{$league->status}} -->
        @if($league->status == 'started')
        <!-- <div class="row">
      <div class="col-md-6">
    
      <div class="timer_box">
          <ul class="list-unstyled list-inline">
            <li class="list-inline-item">
              <div class="time">
                <div class="btn_view">
                  <span>
                    <button id="timerBtn" data-type="{{ $league->draft_timer ? 'stop' : 'start' }}"><i class="{{ $league->draft_timer ? 'fa fa-pause' : 'fa fa-play' }}"></i></button>
                  </span>
                  <span>
                    <button id="refreshTime"><i class="fa fa-repeat"></i></button>
                  </span>
                  <span class="no-padd">
                    <span class="clock"><button><i class="fa fa-clock-o"></i></button>
                      <div class="time_duration">
                        <form id="timerForm">
                          <h4>Change Duration</h4>
                          <p>Current Duration: <span id="currentDuration">{{ $league->timer_value }}</span></p>
                          <p id="demo"></p>
                          @php 
                            $currentDuration = explode(":", $league->timer_value);
                          @endphp
                          <div class="form-group">
                            <span>Hours:</span> <input name="hours" type="number" class="timer" placeholder="00" min="0" max="99" value="{{ $currentDuration[0] }}">
                          </div>
                          <div class="form-group">
                            <span>Minutes:</span> <input name="minutes" type="number" class="timer" placeholder="00" min="0" max="59" value="{{ $currentDuration[1] }}">
                          </div>
                          <div class="form-group">
                            <span>Seconds:</span> <input name="seconds" type="number" class="timer" placeholder="00" min="0" max="59" value="{{ $currentDuration[2] }}">
                          </div>
                          <div class="btn_submit">
                            <button>Submit</button>
                          </div>
                        </form>
                      </div>
                    </span>
                  </span>
                </div>
                <div class="clock">
                  @php 
                    if($league->draft_timer == null){
                      $timer = ($league->remaining_timer) ? $league->remaining_timer : $league->timer_value;
                      $timer = explode(":", $timer);
                      if($timer[0] > 0){
                        $timer = $timer[0].':'.$timer[1];
                      }else{
                        $timer = $timer[1].':'.$timer[2];
                      }
                    }else{
                      $timer = '';
                    }
                  @endphp
                  <h4 id="countDownTimer">{{ $timer }}</h4>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
  
      
  
      <div class="col-md-6">
        <div class="onTheClock">
          <div>
          <p>On The Clock</p>
          @php 
          if($leaguerecord)
          {
            $roundunber=$leaguerecord->round_order;
            $roundorderplus=$leaguerecord->round_order+1;
          }
          else
          {
            $roundorderplus="1";
            $roundunber="1";
          }
          
          @endphp
          <h3>TEAM {{$roundunber}}</h3>
          <p class="upNext">Up Next: Team {{$roundorderplus}}</p>
          </div>
        
        </div>
        
      </div>
      </div>
    </div> -->
        @else
        <div class="col-md-12 text-center">

          <!-- <div class="city_name">
        <h3>{{ ($league->status == 'keeper') ? 'Keeper' : 'Draft in Setup' }} Mode</h3>
      </div> -->
        </div>
        @endif
        <div class="dropDownDiv">
          <div class="edit_revert">
            <ul class="list-unstyled list-inline">
              <li class="list-inline-item draftPlayerLi {{ $league->without_player_count == 0 ? 'hide' : '' }}">
                <div class="select_draft draft_round">
                
                  <div class="form-group drft-plr">
                    <input id="myInput" type="text" name="myCountry" autocomplete="off" placeholder="Enter Player Name">
                    <!-- <select name="draftPlayer" class="draftPlayer select2Drp">
                  <option value="">Draft Player</option>
                  @foreach($players as $player)
                    <option value="{{$player->id}}" data-last_name="{{$player->last_name}}" data-first_name="{{$player->first_name}}" data-team="{{$player->team}}" data-position="{{$player->position}}">{{$player->first_name.' '.$player->last_name.' ('.$player->position.') '}}</option>
                  @endforeach
                </select> -->
                    <button class="draftButton">Draft</button>
                  </div>
                </div>
              </li>
              <!-- <li class="list-inline-item">
            <button>Edit Draft Board</button>
          </li>-->
              <!-- <li class="list-inline-item">
            <button>Save</button>
          </li> -->
            </ul>
          </div>
        </div>
      </div>
      @if($league->status != 'started')
      <div class="col-lg-12 text-center">
        <div class="city_name">
          <h3>{{ $league->name }}</h3>

        </div>
      </div>
      @endif
      @if($league->status != 'started')
      <div class="multiDiv" style="display:none;">
        @else
        <div class="multiDiv">
          @endif
          <div class="container-fluid">

            <div class="row">
              <div class="col-lg-4">
                <div class="d-flex">
                  <div class="timer_box">
                    <ul class="list-unstyled list-inline">
                      <li class="list-inline-item">
                        <div class="time">
                          <div class="btn_view">
                            <span>
                              <button id="timerBtn" data-type="{{ $league->draft_timer ? 'stop' : 'start' }}"><i class="{{ $league->draft_timer ? 'fa fa-pause' : 'fa fa-play' }}"></i></button>
                            </span>
                            <span>
                              <button id="refreshTime"><i class="fa fa-repeat"></i></button>
                            </span>
                            <span class="no-padd">
                              <span class="clock"><button><i class="fa fa-clock-o"></i></button>
                                <div class="time_duration">
                                  <form id="timerForm">
                                    <h4>Change Duration</h4>
                                    <p>Current Duration: <span id="currentDuration">{{ $league->timer_value }}</span></p>
                                    <p id="demo"></p>
                                    @php
                                    $currentDuration = explode(":", $league->timer_value);
                                    @endphp
                                    <div class="form-group">
                                      <span>Hours:</span> <input name="hours" type="number" class="timer" placeholder="00" min="0" max="99" value="{{ $currentDuration[0] }}">
                                    </div>
                                    <div class="form-group">
                                      <span>Minutes:</span> <input name="minutes" type="number" class="timer" placeholder="00" min="0" max="59" value="{{ $currentDuration[1] }}">
                                    </div>
                                    <div class="form-group">
                                      <span>Seconds:</span> <input name="seconds" type="number" class="timer" placeholder="00" min="0" max="59" value="{{ $currentDuration[2] }}">
                                    </div>
                                    <div class="btn_submit">
                                      <button>Submit</button>
                                    </div>
                                  </form>
                                </div>
                              </span>
                            </span>
                          </div>
                          <div class="clock">
                            @php
                            if($league->draft_timer == null){
                            $timer = ($league->remaining_timer) ? $league->remaining_timer : $league->timer_value;
                            $timer = explode(":", $timer);
                            if($timer[0] > 0){
                            $timer = $timer[0].':'.$timer[1];
                            }else{
                            $timer = $timer[1].':'.$timer[2];
                            }
                            }else{
                            $timer = '';
                            }
                            @endphp
                            <h4 id="countDownTimer">{{ $timer }}</h4>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div class="onTheClock">
                    <div>
                      <p>On The Clock</p>
                      @php
                      if($leaguerecord)
                      {
                      $roundunber=$leaguerecord->round_order+1;
                      $teamid=App\Models\LeagueRound::where('league_id',$leaguerecord->league_id)->whereNull('player_id')->orderby('id','asc')->limit(1)->first();
                      if($teamid)
                      {
                      $teamname=\App\Models\LeagueTeam::where('id',$teamid->team_id)->where('league_id',$leaguerecord->league_id)->first();
                      }
                      $nextteamid=App\Models\LeagueRound::where('league_id',$leaguerecord->league_id)->whereNull('player_id')->orderby('id','asc')->limit(2)->get();
                      if(isset($nextteamid) && count($nextteamid)>0)
                      {
                        if(isset($nextteamid[1]->team_id))
                        {
                            $nextteamid=$nextteamid[1]->team_id;
                        }
                        else{
                                $nextteamid=$nextteamid[0]->team_id;
                          }
                      $nextteamname=\App\Models\LeagueTeam::where('id', $nextteamid)->where('league_id',$leaguerecord->league_id)->first();
                      }
                      }
                      else
                      {
                      $roundorderplus="1";
                      $roundunber="1";
                      }

                      @endphp
                      <h3 id="team-round">@if(isset($teamname)){{$teamname->team_name}}@else{{'Team '}} {{$roundunber}}@endif </h3>
                      <p class="upNext" id="upNext">Up Next: @if(isset($nextteamname)){{$nextteamname->team_name}}@else{{'Team '}}{{$roundorderplus ?? ""}}@endif </p>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="city_name">
                  <h3>{{ $league->name }}</h3>

                </div>
              </div>
              <div class="col-lg-4">
                <div class="onTheClock">
                  <div>
                    @php
                    $playerdata=[];
                    if($leaguerecord)
                    {
                    $roundunber=$leaguerecord->round_order;
                    $roundorderplus=$leaguerecord->round_order+1;

                    $roundata=App\Models\LeagueRound::where('league_id',$leaguerecord->league_id)->whereNull('player_id')->orderby('id','asc')->limit(1)->first();
                    if(isset($rounddata) && count($roundata)>0)
                    {
                        $teamidd=\App\Models\LeagueRound::where('round_order',$roundata->round_order-1)->where('round_number',$roundata->round_number)->where('league_id',$leaguerecord->league_id)->first();
                        //dd($teamidd);
                    }
                    
                    if(isset($teamidd->player_id))
                    {
                    $playerdata=\App\Models\Player::where('id',$teamidd->player_id)->first();
                    }
                    else
                    {
                    $playerdata='';
                    }

                    if(isset($teamidd->team_id))
                    {
                    $teamname=\App\Models\LeagueTeam::where('id',$teamidd->team_id)->where('league_id',$leaguerecord->league_id)->first();
                    }
                    }
                    else
                    {
                    $roundorderplus="1";
                    $roundunber="1";
                    }

                    @endphp
                    <p id="team-select">@if(isset($teamname)){{ $teamname->team_name}}@else{{'Team '}} {{$leaguerecord->round_order ?? ''}}@endif Selects</p>
                    <p class="upNext" id="team-slect-fname" style="text-align: center;margin-bottom: 0px;">{{$playerdata->first_name ?? ''}}</p>
                    <h3 style="text-align: center;" id="team-slect-lname">{{$playerdata->last_name ?? ''}}</h3>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>


    <div class="city_board_table">
      <div class="table-responsive">
        <?php if ($league->status != "keeper") { ?>

        <?php } ?>
      </div>

      <table class="table" style="table-layout:fixed;">
        <thead class="thead-dark">
          <tr style="height:1em; ">
            <th style="overflow:hidden;white-space:nowrap;  width:85px;font-size: 10px;">
              @if(isset($_GET['type']) && $_GET['type'] =='collapseview')
              Round
              @endif
            </th>
            <!-- <th style="width:80px"><span></span></th> -->
            @foreach($league->teams as $team)
            <th style="overflow:hidden;white-space:nowrap;  width: 150px;">{{ $team->team_name }}
            </th>

            @endforeach
            <!-- <th style="width:80px"><span>Round</span></th> -->
            <th style="overflow:hidden;white-space:nowrap;width:50px;font-size: 10px;">
              @if(isset($_GET['type']) && $_GET['type'] =='collapseview')
              Round
              @endif
            </th>
          </tr>
        </thead>
        <!-- my new work for keeper list -->
        @if(isset($_GET['type']) && $_GET['type']=="keeperlist")
        <tbody class="tbl-bdy-clr">
          <tr>
            <td></td>
            @foreach($league->teams as $team)
            <td style="overflow:hidden;white-space:nowrap;  width: 150px;">
              @php
              $playersdata=\App\Models\KeeperList::where('team_id',$team->id)->get();
              @endphp
              @foreach($playersdata as $player)
              @php
              $playername=\App\Models\Player::where('id',$player->player_id)->first();
              @endphp
              <br>
              <span class="event" id="{{$team->id.''.$player->player_id}}" data-round="{{$player->round_number}}" data-team="{{$team->id}}" data-player="{{$player->player_id}}" draggable="true">
                <button class="btn btn-secondary" onclick="editkeeperlist('{{$team->id}}','{{$player->round_number}}','{{$playername->first_name.' '.$playername->last_name}}','{{$player->player_id}}')" style="font-size:12px">{{$playername->first_name}} {{ $playername->last_name}} {{ $player->round_number}}</button>
              </span>
              <br>
              @endforeach
              @if(\Auth::user()->role=="Admin")
              <a href="javascript:void(0)" class="addKeeperlist" data-team-id="{{$team->id}}" data-player="{{$player->player_id}}" data-round="{{$player->round_number}}"><i class="fa fa-plus" aria-hidden="true"></i></a>
              @endif
            </td>
            @endforeach
            <td></td>
          </tr>
        </tbody>
        @elseif(isset($_GET['type']) && $_GET['type']=="collapseview")
        <tbody class="tbl-bdy-clr collpaseTable">
          @foreach($league_rounds as $index => $rounds)
          @php
          if($index%2 == 0 && $league->draft_type == 'snake'){
          $leftArrow = '<i class="fa fa-angle-left"></i>';
          $rightArrow = '';
          usort($rounds, "compare2");
          }else{
          $rightArrow = '<i class="fa fa-angle-right"></i>';
          $leftArrow = '';
          usort($rounds, "compare1");
          }
          @endphp
          <tr>
            <!-- <td>{!! $rightArrow !!}</td> -->
            <td>{!! $rightArrow !!} {{ $index }}</td>
            @foreach($rounds as $round)
            @php
            if((int)$round->team_id != (int)$round->old_team_id)
            {
            $background="background:red";
            }else
            {
            $background="background:#b7b7b7";
            }
            @endphp
            <td data-round_id="{{ $round->id }}" data-team_order="{{ $round->team->team_order }}" data-default_order="{{ $index.'.'.$round->default_order }}">
              <div>
                <select style="{{$background}};padding: 8px 10px 7px 0px; " data-view="collapse" id="teamselect" class="teamselect" name="teamselect">
                  @foreach($league->teams as $team)
                  <option value="{{ $team->id.'|'.$index.'|'.$leaugeid.'|'.$round->default_order}}" {{$team->id == $round->team->id  ? 'selected' : ''}}>{{ $team->team_name }}</optio>
                    @endforeach
                </select>
            </td>
            @endforeach
            <td>{{ $index }} {!! $leftArrow !!}</td>
            <!-- <td>{!! $leftArrow !!}</td> -->
          </tr>
          @endforeach
        </tbody>
        @elseif(isset($_GET['type']) && $_GET['type']=="pickview")
        <tbody class="tbl-bdy-clr customeTable">
          <tr>
            <td style="background: #000;color: #fff;vertical-align: middle;position:relative;">
              <p style="transform: rotate(-90deg);font-size: 30px;font-weight: 700;">Draft pick</p>
              <p style="position: absolute;bottom: 25px;left: 8px;">Total Picks</p>
            </td>
            @php
            $max=0;
            @endphp
            @foreach($league->teams as $team)
            @php
            $mydata=\App\Models\LeagueRound::where('team_id',$team->id)->count();
            if($mydata>$max)
            {
            $max=$mydata;
            }
            @endphp
            @endforeach
            @foreach($league->teams as $team)
            @php
            $data=\App\Models\LeagueRound::where('team_id',$team->id)->get();
            @endphp
            <td>
              <table class="table">
                @php
                for($i=0;$i<$max;$i++) { @endphp <tr>
                  <td>
                    <div style="align-item:2px">
                      <p style="margin:0px;">{{$data[$i]->round_number ?? ''}}.{{$data[$i]->round_order ?? ''}}</p>
                    </div>
                  </td>
          </tr>
          @php } @endphp
          <tr style="background-color: black;color:white;z-index:99999;">
            <td style="background-color: black;">
              <div>
                <p style="padding-top:20px;font-size:20px"><strong>{{count($data)}}</strong></p>
              </div>
            </td>
          </tr>

      </table>
      <br>
      </td>
      @endforeach
      <td>asdas</td>
      </tr>
      </tbody>
      @else
      <tbody class="tbl-bdy-clr">
        @foreach($league_rounds as $index => $rounds)
        @php
        if($index%2 == 0 && $league->draft_type == 'snake'){
        $leftArrow = '<i class="fa fa-angle-left"></i>';
        $rightArrow = '';
        usort($rounds, "compare2");
        }else{
        $rightArrow = '<i class="fa fa-angle-right"></i>';
        $leftArrow = '';
        usort($rounds, "compare1");
        }
        @endphp
        <tr>
          <td>{!! $rightArrow !!}</td>
          <!-- <td>{{ $index }}</td> -->
          @foreach($rounds as $round)
          <td data-round_id="{{ $round->id }}" data-team_order="{{ $round->team->team_order }}" data-default_order="{{ $index.'.'.$round->default_order }}">
            @php
            $class='';
            if(isset($teamid))
            {
            if($teamid->round_number ==$round->round_number && $teamid->round_order==$round->round_order && $league->status != 'keeper')
            {
            $class='circle';
            }
            }

            @endphp
            <div style="height:105px;" class="{{$class}}">
              @php
              if((int)$round->team_id != (int)$round->old_team_id)
              {
              $background="background:red";
              }else
              {
              $background="background:#b7b7b7";
              }
              @endphp

              <select style="{{$background}};padding: 8px 10px 7px 0px; " data-view="draft" id="teamselect" class="teamselect" name="teamselect">
                @foreach($league->teams as $team)
                <option value="{{ $team->id.'|'.$index.'|'.$leaugeid.'|'.$round->default_order}}" {{$team->id == $round->team->id  ? 'selected' : ''}}>{{ $team->team_name }}</optio>
                  @endforeach
              </select><br>
              @if(isset($round->player) && isset($round->player->first_name))
              <!-- <span class="indraft_team_name">{{$round->team->team_name}}</span> -->

              <!-- <select style="    background: #b7b7b7;padding: 8px 10px 7px 0px; width:80%;" id="teamselect" name="teamselect">
                  @foreach($league->teams as $team)
                    <option value="{{ $team->id.'|'.$index.'|'.$leaugeid.'|'.$round->player_id }}" {{$team->id == $round->team->id  ? 'selected' : ''}}>{{ $team->team_name }}</optio>
                  @endforeach 
              </select><br> -->
              <span style="font-size:13px;float: left;padding: 5px;">{{$round->player->position }}</span> <span style="float: right;padding: 5px;font-size:13px;">{{ $round->player->team}}</span><br>
              <div class="team_info">
                @if( $league->status == 'keeper')
                <p data-league_id="{{$round->league_id}}" data-team_id="{{$round->team->id}}" data-round_id="{{$round->round_number}}" data-player_id="{{ $round->player->id }}" id="removePlayer"><i class="fa fa-times" aria-hidden="true"></i></p>
                @endif
                <!-- <span style="font-size:13px;">{{$round->player->position }}</span> <span style="font-size:13px;">{{ $round->player->first_name}}</span> <span style="font-size:14px;">{{ $round->player->team}}</span><br> -->
                <span style="font-size:13px;">{{ $round->player->first_name}}</span><br>
                <span style="font-weight:bold;font-size:16px;">{{ $round->player->last_name}}</span><br>
                <span>{{ $index.'.'.$round->default_order }}</span>
              </div>

              @else
              <span class="indraft_team_name" style="display: none">{{$round->team->team_name}}</span>
              <span>{{ $index.'.'.$round->default_order }}</span><br>
              @endif
              @if((!isset($round->player) || !isset($round->player->last_name)) && $league->status == 'keeper')
              <a href="javascript:void(0)" round-number='{{$index}}' round-order='{{$round->default_order}}' class="addKeeper"><i class="fa fa-plus" aria-hidden="true"></i></a>
              @endif
          </td>
          @endforeach
          <!-- <td>{{ $index }}</td> -->
          <td>{!! $leftArrow !!}</td>
        </tr>
        @endforeach
      </tbody>
      @endif
      </table>
      <img src="" id="appendimage">
    </div>
  </div>

  {{--<div class="city_board_table">

    <div class="table-responsive">
  <table class="table">
    <thead class="thead-dark">
    <tr>
      <th></th>
      @foreach($league->teams as $team)
        <th>{{ $team->team_name }}</th>
  @endforeach
  <th></th>
  </tr>
  </thead>
  <tbody class="tbl-bdy-clr">
    <tr>
      <td></td>
      @foreach($league->teams as $team)
      <td>
        @if(isset($team->keepers) && $team->keepers->count() > 0)
        <table class="table innerTable">
          @foreach($team->keepers as $keeper)
          <tr>
            <td>{{ $keeper->player->last_name }} {{ $keeper->round_number }}</td>
          </tr>
          @endforeach
        </table>
        @endif
      </td>
      @endforeach
      <td></td>
    </tr>
    @if(isset($league_squads))
    @foreach($league_squads as $index => $rounds)
    <tr>
      <td></td>
      @foreach($rounds as $round)
      <td data-round_id="{{ $round->id }}">{{ (isset($round->player) && isset($round->player->last_name)) ? $round->player->last_name : '' }}</td>
      @endforeach
      <td></td>
    </tr>
    @endforeach
    @endif
  </tbody>
  </table>
  </div>
  </div>--}}

  <!-- Modal -->
  <div class="modal fade" id="keeperModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Keeper</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="edit_revert">
                <ul class="list-unstyled list-inline">
                  <li class="list-inline-item draftPlayerLi">
                    <div class="select_draft draft_round">
                      <div class="form-group drft-plr">
                        <!-- <select name="" class="keeperPlayer select2Drp">
                      <option value="">Draft Player</option>
                      @foreach($players as $player)
                        <option value="{{$player->id}}" data-last_name="{{$player->last_name}}" data-first_name="{{$player->first_name}}"  data-team="{{$player->team}}" data-position="{{$player->position}}">{{$player->first_name.' '.$player->last_name.' ('.$player->position.') '}}</option>
                      @endforeach
                    </select> -->
                        <input id="myInput2" type="text" name="myCountry" autocomplete="off" placeholder="Enter Player Name">
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary draftbutton1" style="background-image: linear-gradient(to right, #000, #353535);border:1px solid #fff;" id="saveKeeper">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- moda for keeper list -->
  <!-- Modal -->
  <div class="modal fade" id="keeperlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="border:2px solid black;border-radius:20px">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="edit_revert">
                <div class="select_draft draft_round">
                  <div class="row">
                    <div class="col-md-8">
                      <label> </label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span style="background:black;color:white;padding:12px;" class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                        </div>
                        <input style="background:black;color:white;padding:21px;" type="text" id="myInput3" name="myCountry" autocomplete="off" class="form-control" placeholder="Enter Player Name" aria-describedby="basic-addon1">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <label>Round</label>
                      <div class="form-group drft-plr">
                        <input id="keeperlistteamid" type="hidden" name="keeperlistteamid" placeholder="Enter Round number" ? />
                        <input style="background:black;color:white;padding:9px;text-align:center;width: 120%;" id="keeperlistround" type="number" name="keeperlistround" />
                      </div>
                    </div>

                  </div>
                  <button type="button" class="btn btn-primary keeperlistbutton" style="float:left;background:lightseagreen;border:1px solid #fff;width:20%;border-radius:5px;margin:4px">Add</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:20%;border-radius:5px;margin:4px;">Close</button>
                  <button type="button" class="btn btn-primary draftbutton1" style="background:lightskyblue;border:1px solid #fff;width:40% ;border-radius:5px" id="saveKeeperlist">Add to Draft board</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- keeper list edit -->
  <div class="modal fade" id="editkeeperlistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="border:2px solid black;border-radius:20px">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="edit_revert">
                <div class="select_draft draft_round">
                  <div class="row">
                    <div class="col-md-6">
                      <label> </label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span style="background:black;color:white;padding:12px;" class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                        </div>
                        <input style="background:black;color:white;padding:21px;" type="text" id="myInput4" name="myCountry" autocomplete="off" class="form-control" placeholder="Enter Player Name" aria-describedby="basic-addon1">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group drft-plr">
                        <label>Round</label>
                        <input id="editkeeperlistteamid" type="hidden" name="editkeeperlistteamid" placeholder="Enter Round number" />
                        <input type="hidden" id="oldroundunber">
                        <input type="hidden" id="oldplayerid">
                        <input style="background:black;color:white;padding:9px;width: 120%" id="editkeeperlistround" type="number" name="editkeeperlistround" placeholder="Enter Round number" />

                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group" id="roundappend">


                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary updatekeeperlistbutton" style="background:lightseagreen;width:20%;border-radius:5px;margin:4px;border:1px solid #fff;">Add</button>
                  <button type="button" class="btn btn-danger" id="removekeeperlist" style="width:20%;border-radius:5px;margin:4px;">Remove</button>
                  <button type="button" class="btn btn-primary" style="background:lightskyblue;border:1px solid #fff;width:40%; border-radius:5px" id="updateKeeperlist">Add to Draft board</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <form method="POST" id="canvasform" class="d-none" enctype="multipart/form-data" action="{{url('/league/save-league-images')}}" id="myForm">
    @csrf
<input type="hidden" name="id" value="{{$league->id}}">
<input type="hidden" name="img_val" id="img_val" value="" />
</form> -->
  @endsection
  @section('js')
  <script type="text/javascript">
    var durationTime = '{{ ($league->remaining_timer) ? $league->remaining_timer : $league->timer_value }}';
    var leagueId = '{{ $league->id }}';
    var laterDateTime = '{{ ($league->draft_timer) ? $league->draft_timer : '
    ' }}';
  </script>
  <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/league/timer.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/league/draft.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>
  <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
       setTimeout(function(){ 
            //html 2 canvas
                html2canvas(document.body).then(function(canvas) {
                var img = canvas.toDataURL()
                //$('#img_val').val(canvas.toDataURL("image/png"));
                 //document.getElementById("canvasform").submit();
                //$("#appendimage").attr('src',img);
                $.ajax({
                    "type": "POST",
                    "url": "{{url('/league/save-league-images')}}",
                    "data":{"imageData": img,id:leagueId } //Send to WebMethod
                }).done(function(o) {
                    console.log(["Response:" , o]); 
                });

            });

            }, 3000);
    });
</script>
  <script>
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }


    $('#zoom-in').click(function() {
      updateZoom(0.1);
    });

    $('#zoom-out').click(function() {
      updateZoom(-0.1);
    });


    zoomLevel = 1;

    var updateZoom = function(zoom) {
      zoomLevel += zoom;
      $('body').css({
        zoom: zoomLevel,
        '-moz-transform': 'scale(' + zoomLevel + ')'
      });
    }

    function editkeeperlist(teamid, roundnumber, playername, playerid) {
      //get posibble round order against round and team
      $.ajax({
        url: "/league/" + leagueId + "/get-round-order",
        method: "POST",
        data: {
          roundnumber: roundnumber,
          teamid: teamid
        },
        success: function(res) {
          res = JSON.parse(res);
          if (res.length > 1) {
            list = '<label>Pick</label><select id="roundorder" class="form-control" style="width:100px;background:black;color:white;padding:9px;height:44px">';
            for (i = 0; i < res.length; i++) {
              list += '<option value=' + res[i].round_order + ' selected>' + res[i].round_order + '</option>';
            }
            list += '</select>';
            $("#roundappend").html(list);
          }
        }
      })

      //$("#myInput4").val(playername);
      $("#oldplayerid").val(playerid);
      $("#editkeeperlistteamid").val(teamid);
      $("#oldroundunber").val(roundnumber);
      $("#editkeeperlistround").val(roundnumber);
      $("#editkeeperlistModal").modal('show');
    }

    $(document).ready(function() {
      var oldroundunber;
      var oldteamid;
      var playerid;
      $('.event').on("dragstart", function(event) {
        var dt = event.originalEvent.dataTransfer;
        dt.setData('Text', $(this).attr('id'));
        oldroundunber = $(this).attr('data-round');
        oldteamid = $(this).attr('data-team');
        playerid = $(this).attr('data-player');
      });
      $('table td').on("dragenter dragover drop", function(event) {
        event.preventDefault();
        if (event.type === 'drop') {
          var data = event.originalEvent.dataTransfer.getData('Text', $(this).attr('id'));
          de = $('#' + data).detach();
          var newteamid = $(this).children("a").attr("data-team-id");
          de.prependTo($(this));
          $.ajax({
            url: "/league/" + leagueId + "/movekeeperlist",
            method: "get",
            data: {
              id: playerid,
              oldteamid: oldteamid,
              newteamid: newteamid,
              oldroundunber: oldroundunber
            },
            success: function(res) {
              if (res == "success") {
                document.getElementById("playerBeep").play();
                window.location =
                  "/league/" +
                  $("input[name='league_id']").val() +
                  "/draft?type=keeperlist";
              } else {
                alert("something went wrong");
              }
            },
          });
        };
      });
    })
  </script>
  <script>
    $("#editkeeperlistround").change(function() {
      roundnumber = $(this).val();
      teamid = $("#editkeeperlistteamid").val();
      //get posibble round order against round and team
      $.ajax({
        url: "/league/" + leagueId + "/get-round-order",
        method: "POST",
        data: {
          roundnumber: roundnumber,
          teamid: teamid
        },
        success: function(res) {
          res = JSON.parse(res);
          if (res.length > 1) {
            list = '<label>Pick</label><select id="roundorder" class="form-control"  style="width:100px;background:black;color:white;padding:9px;height:44px">';
            for (i = 0; i < res.length; i++) {
              list += '<option value=' + res[i].round_order + ' selected>' + res[i].round_order + '</option>';
            }
            list += '</select>';
            $("#roundappend").html(list);
          } else {
            $("#roundappend").html('');
          }
        }
      })

    })
  </script>
  <script type="text/javascript">
      $(".mybutton").on('click',function(){
        var leagueStatus = $(this).attr('data-mode');
        let modeId = leagueStatus == "started" ? "keeperMode" : "draftMode";
        if(modeId=="keeperMode")
        {
            title='Live Draft Mode';
        }
        else{
            title='Back to the Lab!';
        }
        swal({
                title: ""+title+"",
                buttons: false,
                timer: 1500,
            }).then(() => {
                changeLeagueStatus(leagueStatus);
            });
      })
      function changeLeagueStatus(status) {
    $.ajax({
        type: "POST",
        url: "/league/" + $("input[name='league_id']").val() + "/changeStatus",
        data: { status: status },
        success: function (response) {
            if (response.status == 200) {
                successMessage(response.message);
                    window.location.href =
                        "/league/" + response.data.id + "/draft";
            } else {
                errorMessage(response.message);
            }
        },
    });
}
  </script>
  @endsection