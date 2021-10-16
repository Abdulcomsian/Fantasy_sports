@extends('layouts.default')
@section('title', 'City Chart')
@section('css')
<style type="text/css">
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

  <div class="fixedBanner">
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
        <div class="col-md-6">
          <form id="updateLeague" class="draftFrom">

            <div class="list_edit">
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
            <div class="list_edit">
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
          <h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
        </div>


      </div>
      <input type="hidden" name="league_id" value="{{ $league->id }}">

    </div>
    <div class="league-div">
      <div class="container-fluid assign_order">
        <div class="row">
          <div class="col-lg-6">
            <div class="d-flex">
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft')}}">Draft Board</a></h2>
              @if($league->status=="keeper")
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=keeperlist')}}">Keeper List</a></h2>
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=collapseview')}}">Collapse View</a></h2>

              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=pickview')}}">Pick View</a></h2>
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="#">League Notes</a></h2>
              @endif
              @if($league->status!="keeper")
              <h2 class=" " style="width:20%;"><a style="color:#fff" href="#">GM Dashboard</a></h2>

              <h2 class=" " style="width:20%;"><a style="color:#fff" href="{{url('/league/'.$league->id.'/roster-view')}}">Roster View</a></h2>
              <h2 class=" " style="width:20%;padding: 14px 33px;"><a style="color:#fff" href="#">Chat</a></h2>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="container ">
        <!-- {{$league->status}} -->
        @if($league->status == 'started')
        @else
        <div class="col-md-12 text-center">
        </div>
        @endif
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
            </th>
            <!-- <th style="width:80px"><span></span></th> -->
            @foreach($league->teams as $team)
            <th style="overflow:hidden;white-space:nowrap;  width: 150px;">{{ $team->team_name }}
            </th>
            @endforeach
            <!-- <th style="width:80px"><span>Round</span></th> -->
            <th style="overflow:hidden;white-space:nowrap;width:50px;font-size: 10px;">
            </th>
          </tr>
        </thead>
        <tbody class="tbl-bdy-clr">
          @php
          $rosterdata=\App\Models\Roster::where('league_id',$league->id)->get();
          @endphp

          @foreach($rosterdata as $data)
          <tr>
            <td style="line-height:130px">
              {{$data->position}}
            </td>
            @foreach($league->teams as $team)
            @php
            $playerdata=\App\Models\RosterTeamplayer::where(['team_id'=>$team->id,'rosters_id'=>$data->id,'league_id'=>$league->id])->first();
            if($playerdata)
            {
            $playername=\App\Models\Player::where('id',$playerdata->player_id)->first();
            }
            else{
            $playername='';
            }

            @endphp
            <td style="border: 1px solid gray;">
              <div style="min-height:140px;background:@if($playername){{$data->color}}@endif">
                <span style="font-size:13px;float: left;padding: 5px;">{{$playername->position ?? ''}}</span> <span style="float: right;padding: 5px;font-size:13px;">{{$playername->team ?? ''}}</span><br>
                @if($playerdata)
                <span style="font-size:13px;">{{ $playername->first_name ?? ''}}</span><br>
                <span style="font-weight:bold;font-size:22px;">{{ $playername->last_name ?? ''}}</span><br>
                @else
                {{$data->position}}
                @endif
              </div>
            </td>
            @endforeach
            <td style="line-height:130px">
              {{$data->position}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

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