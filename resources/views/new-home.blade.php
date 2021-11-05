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

    .contentDiv .viewDiv .leagueDiv {
        margin-bottom: 20px
    }

    .contentDiv .viewDiv .leagueDiv h4 {
        color: #fff;
        text-align: center;
        font-size: 18px;
    }

    .contentDiv .viewDiv .leagueDiv img {
        height: 150px;
    }

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
</style>
<div class="create_league_table assign_order the_lottery draft_boards draft_room">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-3">
            </div> -->
            <div class="col-md-12">
                <div class="side_detail">
                    <h4>Draft Room</h4>
                </div>
            </div>
            <div class="col-md-12">
                <div class="contentDiv">
                    <div class="sideBar">
                        <h4>League Draft Board</h4>
                        <ul>
                            <a href="{{url('/home')}}">
                                <li class="{{$activeclass ?? ''}}">Active</li>
                            </a>
                            <a href="{{url('/completed-league')}}">
                                <li class="{{$compclass ?? ''}}">Completed Draft</li>
                            </a>
                            <a href="{{url('/league/create')}}">
                                <li>Create League</li>
                            </a>
                            <a href="{{url('/renew/league')}}">
                                <li class="{{$renewclass ?? ''}}">Renew Exsisting League</li>
                            </a>
                            <li>Join Exsisting League</li>
                            <li>
                                <input type="text" placeholder="Enter Join Key ....">
                            </li>
                        </ul>
                    </div>
                    <div class="viewDiv">
                        <div class="row">
                            @foreach($leagues as $league)
                            <div class="col-md-4">
                                <div class="leagueDiv">
                                    <h4>{{$league->name}}</h4>
                                    <a href="{{url('league/'.$league->id.'/settings')}}">
                                        <img src="{{asset('images/City_Chart.png')}}" alt="" data-id="{{$league->id}}" class="img-fluid @if(isset($renewclass)){{'renew'}}@endif">
                                    </a>
                                </div>
                            </div>
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
            title: 'Are you sure to renew League?',
            content: {
                element: "input",
                attributes: {
                    placeholder: "Type League Name",
                    type: "text",
                    required: true,
                },
            },
            buttons: true,
        }).then((result) => {
            if (result) {
                $("#leaguename").val(result);
                $('#renewform').submit();
            }
        });

    })
</script>

@endsection