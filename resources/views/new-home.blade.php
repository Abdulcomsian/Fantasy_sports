@extends('layouts.default')
@section('title', 'Home')
@section('content')
<style>
    .contentDiv {
        display: flex;
        justify-content: space-between;
    }

    .contentDiv .sideBar {
        width: 30%;
    }

    .contentDiv .sideBar ul {
        list-style: none;
        padding: 0px;
    }

    .contentDiv .sideBar ul li {
        color: #fff;
        padding: 10px 15px;
        font-size: 22px;
    }

    .contentDiv .viewDiv {
        width: 65%;
    }
    .page-item.active .page-link {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
    }
    .page-link{
        color: #000 !important;
    }
    .contentDiv .viewDiv .leagueDiv {
        margin-bottom: 80px;
        transition: all 3s;
        position: relative;
    }

    .contentDiv .viewDiv .leagueDiv h4 {
        color: #fff;
        text-align: center;
        font-size: 18px;
    }

    .contentDiv .viewDiv .leagueDiv img {
        height: 150px;
        width: 100%;
    }

    .contentDiv .viewDiv .leagueDiv .imgDiv {
        position: relative;
    }

    .contentDiv .viewDiv .leagueDiv .imgDiv {
        position: relative;
       

    }

    /* .contentDiv .viewDiv .leagueDiv .imgDiv:before {
        content: "";
        position: absolute;
        top: -1px;
        left: 0px;
        width: 22px;
        height: 22px;
        border-top: 3px solid #fff;
        border-left: 3px solid #fff;
        transition: 0.3s;
        z-index: 9999999;

    } */

    .contentDiv .viewDiv .leagueDiv .imgDiv:hover {
        box-shadow: 0 0 50px #fecc08;
        transition-delay: 0.3s;
    }

    /* .contentDiv .viewDiv .leagueDiv .imgDiv:hover:before {
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        transition-delay: 0.3s;
    } */

    /* .contentDiv .viewDiv .leagueDiv .imgDiv:after {
        content: "";
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 22px;
        height: 22px;
        border-bottom: 3px solid #fff;
        border-right: 3px solid #fff;
        transition: 0.3s;
        z-index: 9999999;
    }

    .contentDiv .viewDiv .leagueDiv .imgDiv:hover:after {
        width: 100%;
        height: 100%;
        bottom: 0px;
        right: 0px;
        transition-delay: 0.3s;
    } */

    .contentDiv .sideBar h4 {
        font: 900 20px "Lato", sans-serif;
        color: #fff;
        text-transform: uppercase;
        position: relative;
        margin-bottom: 30px;
    }

    .contentDiv .sideBar ul li.active {
        background-image: linear-gradient(to right, #000, #353535);
        font: 700 22px "Neometric";
        color: #fff;
        border: 1px solid #fff;
        text-align: center;
        margin: 0 auto 20px;
    }

    .swal-modal {
        width: 700px;
    }

    .swal-text {
        font-size: 40px;
        font-weight: bold;
        color: black
    }

    .swal-footer {
        text-align: center;
    }

    .operationDiv {
        position: absolute;
        right: 8px;
        top: 0px;
        z-index: 999999999;
    }

    .operationDiv a {
        font-size: 18px;
    }

    .season_fall {
        background: none !important;
        background-color: #000 !important;
    }

    /* .overlayImg {
        width: 100%;
        min-height: 30px;
        position: absolute;
        bottom: 0;
        opacity: .9;
        background: linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(75, 73, 68, 1) 100%);
    } */
</style>
<div class="create_league_table assign_order the_lottery draft_boards draft_room">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-3">
            </div> -->
            <div class="col-md-12">
                <div class="side_detail">
                <h4>League Draft Boards</h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="contentDiv">
                    <div class="sideBar">
                       
                        <ul>
                            <a href="{{url('/home')}}">
                                <li class="{{$activeclass ?? ''}}">Active</li>
                            </a>
                            <a href="{{url('/completed-league')}}">
                                <li class="{{$compclass ?? ''}}">Completed Drafts</li>
                            </a>

                            <a href="{{url('/league/create')}}">
                                <li>Create League</li>
                            </a>
                            <a href="{{url('/renew/league')}}">
                                <li class="{{$renewclass ?? ''}}">Renew Existing League</li>
                            </a>
                            <!-- <li>Join Exsisting League</li>
                            <li>
                                <form id="checkLeagueExist">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="text" name="joiner_key" placeholder="Enter Join Key .." class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn_green">Join</button>
                                        </div>

                                    </div>
                                </form>
                            </li> -->
                        </ul>
                    </div>
                    <div class="viewDiv">
                        <div class="row">
                            @foreach($leagues as $league)
                            @if(\Auth::user()->role=="Admin" || $league->created_by==\Auth::user()->id)
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>
                                        {{$league->name}}
                                    </h4>
                                    @php
                                    $imgname="leagueimages/".$league->id."imgscreen.png";
                                    @endphp
                                    <div class="imgDiv">
                                        <a href="{{url('league/'.$league->id.'/draft')}}">
                                            <img src="@if(file_exists(public_path($imgname))){{asset($imgname)}}@else{{asset('images/City_Chart.png')}}@endif" alt="" data-id="{{$league->id}}" class="img-fluid @if(isset($renewclass)){{'renew'}}@endif">

                                        </a>
                                        <div class="operationDiv">
                                            <a href="{{url('league/'.$league->id.'/settings')}}" class="text-white"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                            <a href="{{url('league/'.$league->id.'/delete')}}" class="text-white delete-league"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="overlayImg"></div>
                                </div>
                            </div>
                            @else
                            @php
                            $dta=\DB::table('league_user')->where(['league_id'=>$league->id,'user_id'=>\Auth::user()->id])->first();
                            @endphp
                            @if($dta)
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>
                                        <!-- @if($dta->permission_type=="1" || $dta->permission_type=="2")
                                        <sapn style="font-size:10px;padding:20px;">
                                            <a href="{{url('league/'.$league->id.'/settings')}}" class="text-white">Setting</a>
                                        </sapn>
                                        @endif -->
                                        {{$league->name}}
                                    </h4>
                                    <div class="operationDiv">
                                        @if($dta->permission_type=="1" || $dta->permission_type=="2")
                                        <a href="{{url('league/'.$league->id.'/settings')}}" class="text-white"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                        @endif
                                    </div>
                                    <a href="{{url('league/'.$league->id.'/draft')}}">
                                        <img src="@if(file_exists(public_path($imgname))){{asset($imgname)}}@else{{asset('images/City_Chart.png')}}@endif" alt="" data-id="{{$league->id}}" class="img-fluid @if(isset($renewclass)){{'renew'}}@endif">
                                    </a>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <center>{{$leagues->links("pagination::bootstrap-4")}}</center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="renewleaguemodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Renew League</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to renew it?</p>
            </div>
            <div class="modal-footer">
                <form method="post" id="renewform" action="{{url('renew-league')}}">
                    @csrf
                    <input type="hidden" id="lagueid" name="leagueid" value="" />
                    <input type="hidden" id="leaguename" name="leaguename" value="" />
                    <button type="submit" class="btn btn-primary">Yes Renew It!</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/home/joiner.js') }}"></script>
<script>
    $(".renew").on('click', function(e) {
        e.preventDefault();
        leagueid = $(this).attr('data-id');
        $("#lagueid").val(leagueid);
        // $("#renewleaguemodal").modal();
        swal({
            text: 'Renew league?',
            title: 'Renew league will duplicate and reset entire  league board but will keep all current settings',
            content: {
                element: "input",
                attributes: {
                    placeholder: "Enter League Name",
                    type: "text",
                    required: true,
                },

            },
            buttons: ["Cancel", "Confirm"],
        }).then((result) => {
            if (result) {
                $("#leaguename").val(result);
                $('#renewform').submit();
            }
        });

    })
    $(".leagueDiv").mouseenter(function() {
        $(this).css("transform", "scale(1.1)")
        $(".leagueDiv").css("opacity", ".5")
        $(this).css("opacity", "1")
        $(".operationDiv").css("right", "8px")
    })
    $(".leagueDiv").mouseleave(function() {
        $(".operationDiv").css("right", "8px")
        $(this).css("transform", "initial")
        $(".leagueDiv").css("opacity", "1")
        $(this).css("opacity", "1")

    })
    //delete league
    $(".delete-league").on('click', function(e) {
        e.preventDefault();
        url = $(this).attr('href');
        swal({
            title: 'Delete League?',
            buttons: true,
        }).then((result) => {
            if (result) {
                window.location = url;
            }
        });
    })
</script>

@endsection