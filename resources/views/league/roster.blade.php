@extends('layouts.default')
@section('title', 'City Chart')
@section('css')
<style type="text/css">
.dropdown a:hover{
  background:#000 !important;;
  color:#fff !important;
}
.swal-modal{
  background-color: rgba(255, 255, 255, 0.3);
 -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
  
}
.swal-title{color:#000 !important;}
.swal-title:first-child{margin-top:0px;}
#removePlayer img{
  
  width: 23px;
  background-color: rgb(0 0 0 / 20%);
  padding: 0 2px;
}
.swal-overlay--show-modal .swal-modal{height:auto !important;}
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

  <div class="fixedBanner"  style="background:#000 !important;">
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
      <div class="col-md-1 text-left" style="font-weight:bold;    font-family: olympus !important;color:#fff;    position: relative;left: -40px;top: -5px;">THE<br>OFFSEASON<br>GM</div>
        <div class="col-md-6">
        <nav class="navbar navbar-expand-lg " style="background-color:#000;">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup" style="top: -15px;position: relative;">
                <ul class="navbar-nav">
                <li class="nav-item" style="text-align: center;">
                <img src="{{ asset('images/draft.png') }}" style="width:27px; position: relative; top: -5px;" /> 
                  <a class="nav-link active" style="color:#fff" href="{{url('/league/'.$league->id.'/draft')}}">Draft Board</a>
                </li>
                <li class="nav-item" style="text-align: center;">
                <img src="{{ asset('images/keeper.png') }}" style="width:51px; position: relative; top: -5px;" /> 
                    <a class="nav-link" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=keeperlist')}}">Keeper List</a>
                </li>
                <li class="nav-item dropdown" style="text-align: center;">
                <img src="{{ asset('images/views.png') }}" style="width:42px; position: relative; top: -5px;" /> 
                    <a class="nav-link dropdown-toggle" style="color:#fff" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Views
                    </a>
                    <div class="dropdown-menu" style="background-color:#000 !important;" aria-labelledby="navbarDropdownMenuLink">
                      <a class="dropdown-item"  style="color:#fff" href="{{url('/league/'.$league->id.'/roster-view')}}"><img src="{{ asset('images/right-angle.png') }}" style="width:30px; position: relative; top: -7px;" />   Rosters</a>
                      <a class="dropdown-item" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=pickview')}}"><img src="{{ asset('images/right-angle.png') }}" style="width:30px; position: relative; top: -7px;" />   Picks</a>
                      <a class="dropdown-item" style="color:#fff" href="{{url('/league/'.$league->id.'/draft?type=collapseview')}}"><img src="{{ asset('images/right-angle.png') }}" style="width:30px; position: relative; top: -7px;" />   Collapse</a>
                    </div>
                </li>
                <li class="nav-item"  style="text-align: center;">
                  <img src="{{ asset('images/league.png') }}" style="width:28px; position: relative; top: -5px;" /> 
                    <a class="nav-link" style="color:#fff" href="#">League Notes </a>
                </li>
                <li class="nav-item"  style="text-align: center;">
                    <img src="{{ asset('images/gm.png') }}" style="width:40px; position: relative; top: -5px;" /> 
                    <a class="nav-link" style="color:#fff" href="#">GM Dashboard</a>
                </li>
                <li class="nav-item"  style="text-align: center;">
                  <img src="{{ asset('images/chat.png') }}" style="width:24px; position: relative; top: -5px;" /> 
                  <a class="nav-link" style="color:#fff" href="#">Chat</a>
                </li>
                <li class="nav-item"  style="text-align: center;">
                  <img src="{{ asset('images/draft-room.png') }}" style="width:50px; position: relative; top: -5px;" /> 
                  <a class="nav-link" style="font-size:16px; text-transform: capitalize !important;color:red;font-family:dead !important;" href="{{url('/home/') }}">Draft Room</a>
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
                
                <div class="custom-control custom-switch d-flex" >                 
                    <button style="margin-right: 5px;" class="btn btn-success mybutton @if($league->status=='keeper') green @else black @endif" data-mode="keeper">Edit Mode</button>
                    <button class="btn btn-success mybutton @if($league->status=='started') green @else black @endif "   data-mode="started">@if($league->status=="keeper"){{'Live Draft Mode'}}@else{{'Live Draft Mode'}}@endif</button>
                  </div>
                
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
        @if(auth::user()->role=="Admin")
          <div class="col-lg-2 text-right">
            <div class="d-flex" style="justify-content:flex-end;">
            <p style="margin: 0px 30px;" type="button" id="zoom-out"><img style="width:40px;" src="{{ asset('images/minus.png') }}" /></p>
              <p style="" type="button" id="zoom-in"><img style="width:40px;" src="{{ asset('images/plus.png') }}" /></p>
              @if(auth::user()->role=="Admin" || $league->created_by==\Auth::user()->id)
        
        <p style="width:70%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}"><img style="width:40px;" src="{{ asset('images/setting.png') }}" /></a></p>
      
      @else
          @php
          $dta=\DB::table('league_user')->where(['league_id'=>$league->id,'user_id'=>\Auth::user()->id])->first();
          @endphp
          @if($dta->permission_type==1 || $dta->permission_type==2)
          
            <h2 style="width:70%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
          
          @endif
      @endif
            </div>
          </div>
        @endif
        <!-- @if(auth::user()->role=="Admin" || $league->created_by==\Auth::user()->id)
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
        @endif -->


      </div>
      <input type="hidden" name="league_id" value="{{ $league->id }}">

    </div>
    <div class="league-div">
     
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
    <div class="city_board_table test2">
      <div class="table-responsive">
        <?php if ($league->status != "keeper") { ?>

        <?php } ?>
      </div>

      <table class="table" style="margin-bottom:0px;"> 
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
          $rosterdata=\App\Models\Roster::where('league_id',$league->id)->orderBy('orderno','asc')->get();
          @endphp
          @foreach($rosterdata as $data)
          <tr>
            <td style="padding-top:10px;background:#000; color:#fff;">
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
            <td style="border: 1px solid gray;height: 40px;max-height:40px;">
              <div style="min-height:40px;background:@if($playername){{$data->color}};color:white;@endif">
                <span style="font-size:13px;float: left;padding: 5px;">{{$playername->position ?? ''}}</span> <span style="float: right;padding: 5px;font-size:13px;">{{$playername->team ?? ''}}</span>
                @if($playerdata)
                <span style="font-size:13px;">{{ $playername->first_name ?? ''}}</span>
                <span style="font-weight:bold;font-size:13px;">{{ $playername->last_name ?? ''}}</span>
                @else
                {{$data->position}}
                @endif
              </div>
            </td>
            @endforeach
            <td style="line-height: 80px;background:#000; color:#fff;">
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <p style="color:#fff;text-align: center;background: #000;padding: 20px 0;">WHERE THE FANTASY SEASON <span style="color:red;">NEVER ENDS</span></p>
    </div>
  </div>

  @endsection
  @section('js')
  <script type="text/javascript">
    $(document).ready(function(){
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
                changeLeagueStatus1(leagueStatus);
            });
      })
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
function changeLeagueStatus1(status) {
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