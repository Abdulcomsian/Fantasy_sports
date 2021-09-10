@extends('layouts.default')
@section('title', 'City Chart')
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
<div class="fixedBanner">
<div class="overlay"></div>
  <audio id="timerBeep">
    <source src="{{ asset('beeps/beep.mp3') }}"/>
    <source src="{{ asset('beeps/beep.wav') }}" />
  </audio>
  <audio id="playerBeep">
    <source src="{{ asset('beeps/playerBeep.mp3') }}"/>
    <source src="{{ asset('beeps/playerBeep.wav') }}" />
  </audio>
  <div class="container create_league_table assign_order the_lottery traders city_charts" style="padding-top:35px;">
  <div class="row">
        <div class="col-md-6">
        <form id="updateLeague" class="draftFrom">

    <div class="list_edit">
      <div class="row">
        <div class="col-md-6 no-bdr">
          <h4><span><i class="fa fa-star yellow"></i>Edit Mode</span></h4>
        </div>
        <div class="col-md-6">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input lequeMode2" id="keeperMode" {{ $league->status == 'keeper' ? 'checked' : '' }} value="keeper">
            <label class="custom-control-label on-off" for="keeperMode"></label>
          </div>
        </div>
      </div>
    </div>
    <div class="list_edit">
      <div class="row">
        <div class="col-md-6 no-bdr">
          <h4><span><i class="fa fa-star yellow"></i>Draft Mode</span></h4>
        </div>
        <div class="col-md-6">
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input lequeMode2" id="draftMode" {{ $league->status == 'started' ? 'checked' : '' }} value="started">
            <label class="custom-control-label on-off" for="draftMode"></label>
          </div>
        </div>
      </div>
    </div>
    </form>
        </div>
        <div class="col-md-2">
          <h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft</a></h2>
        </div>
        <div class="col-md-2">
        <p onclick="myFunction()" class="dropbtn">Select <i class="fa fa-angle-down" aria-hidden="true"></i></p>
          <div id="myDropdown" class="dropdown-content">
            <a href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad</a>
            <a href="{{url('draft-roaster')}}">Draft Roaster</a>
          </div>
        <!-- <h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad<a/></h2> -->
        </div>
        <div class="col-md-2">
          <h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
        </div>
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
  <div class="container">
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
                  <input id="myInput" type="text" name="myCountry" placeholder="Enter Player Name">
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
  </div>
  </div>


<div class="city_board_table">
  <div class="table-responsive">
  <?php if($league->status!="keeper"){ ?>

  
  
  
  
  
  <?php } ?>
  <div class="multiDiv">
  <div class="container">
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
    <div class="col-lg-4">
    <div class="city_name">
          <h3>{{ $league->name }}</h3>
         
        </div>
    </div>
    <div class="col-lg-4">
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
  </div>
  </div>
  
 
 
        </div>

       
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th style="width:20px"></th>
          <th style="width:80px"><span>Round</span></th>
          @foreach($league->teams as $team)
            <th style="width: 150px;">{{ $team->team_name }}</th>
          @endforeach
          <th style="width:80px"><span>Round</span></th>
          <th style="width:20px"></th>
        </tr>
      </thead>
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
            <td>{{ $index }}</td>
            @foreach($rounds as $round)
              <td 
                data-round_id="{{ $round->id }}" 
                data-team_order="{{ $round->team->team_order }}" 
                data-default_order="{{ $index.'.'.$round->default_order }}"
              ><div style="min-height:140px;">
                @php
                    if((int)$round->team_id != (int)$round->old_team_id)
                    {
                        $background="background:red";
                    }else
                    {
                        $background="background:#b7b7b7";
                    }
                @endphp
                
              <select style="{{$background}};padding: 8px 10px 7px 0px; " id="teamselect" name="teamselect">
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
                <span style="font-size:13px;float: left;padding: 5px;">{{$round->player->position }}</span>  <span style="float: right;padding: 5px;font-size:13px;">{{ $round->player->team}}</span><br>
                <div class="team_info">
                <a href="javascript:void(0)"  data-league_id = "{{$round->league_id}}" data-team_id = "{{$round->team->id}}" data-round_id="{{$round->round_number}}" data-player_id="{{ $round->player->id }}" id="removePlayer"><i class="fa fa-times" aria-hidden="true"></i></a><br>
                  <!-- <span style="font-size:13px;">{{$round->player->position }}</span> <span style="font-size:13px;">{{ $round->player->first_name}}</span> <span style="font-size:14px;">{{ $round->player->team}}</span><br> -->
                  <span style="font-size:13px;">{{ $round->player->first_name}}</span><br>
                  <span style="font-weight:bold;font-size:22px;">{{ $round->player->last_name}}</span><br>
                  <span>{{ $index.'.'.$round->default_order }}</span>
                </div>
              
                @else
                <span class="indraft_team_name" style="display: none">{{$round->team->team_name}}</span>
                  <span>{{ $index.'.'.$round->default_order }}</span>
                @endif
                
                @if((!isset($round->player) || !isset($round->player->last_name)) && $league->status == 'keeper')
                  <br>
                  <a href="javascript:void(0)" round-number='{{$index}}' round-order='{{$round->default_order}}' class="addKeeper"><i class="fa fa-plus" aria-hidden="true"></i></a>
                @endif
              </td>
            @endforeach
            <td>{{ $index }}</td>
            <td>{!! $leftArrow !!}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
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
                      <input id="myInput2" type="text" name="myCountry" placeholder="Enter Player Name">
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
@endsection
@section('js')
<script type="text/javascript">
  var durationTime = '{{ ($league->remaining_timer) ? $league->remaining_timer : $league->timer_value }}';
  var leagueId = '{{ $league->id }}';
  var laterDateTime = '{{ ($league->draft_timer) ? $league->draft_timer : '' }}';
</script>
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/timer.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/draft.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
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
</script>
@endsection
