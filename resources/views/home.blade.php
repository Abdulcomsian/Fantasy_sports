@extends('layouts.default')
@section('title', 'Home')
@section('content')
<div class="create_league_table assign_order the_lottery draft_boards draft_room">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="side_detail">
                    <h4>Draft Room</h4>
                </div>
                <div class="alert alert-warning alert-dismissible hide" role="alert">
                    <span class="message"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="successMessage"></div>
                <form id="checkLeagueExist">
                    <h4>Create League</h4>
                    <h6>Join Existing League</h6>

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
                <form>
                    @if(count($leagues['setup']) > 0)
                    <div class="check_view">
                        <input type="checkbox" id="inSetupLeagues" class="largerCheckbox"> <span>In Setup</span>
                        <div class="hide" id="inSetupLeaguesDiv">
                        @foreach($leagues['setup'] as $league)
                            <ul class="list-unstyled list-inline" id="leagueId-{{$league->id}}">
                                <li class="list-inline-item">
                                    <p>{{$league->name}}</p>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{url('league/'.$league->id.'/draft')}}">
                                        <img src="{{ asset('images/table.png') }}">
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{url('league/'.$league->id.'/settings')}}">
                                        <img src="{{ asset('images/settings.png') }}">
                                    </a>
                                </li>
                                @if($league->created_by == Auth::id())
                                <li class="list-inline-item">
                                    <button class="btn btn_delete" data-id="{{$league->id}}">Delete</button>
                                </li>
                                @endif
                            </ul>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    @if(count($leagues['started']) > 0)
                    <div class="check_view">
                        <input type="checkbox" id="startedLeagues" class="largerCheckbox"> <span>In Progress</span>
                        <div class="hide" id="startedLeaguesDiv">
                        @foreach($leagues['started'] as $league)
                            <ul class="list-unstyled list-inline" id="leagueId-{{$league->id}}">
                                <li class="list-inline-item">
                                    <p>{{$league->name}}</p>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{url('league/'.$league->id.'/draft')}}">
                                        <img src="{{ asset('images/table.png') }}">
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{url('league/'.$league->id.'/settings')}}">
                                        <img src="{{ asset('images/settings.png') }}">
                                    </a>
                                </li>
                                @if($league->created_by == Auth::id())
                                <li class="list-inline-item">
                                    <button class="btn btn_delete" data-id="{{$league->id}}">Delete</button>
                                </li>
                                @endif
                            </ul>
                        @endforeach
                        </div>
                    </div>
                    @endif
                </form>
            </div>
            <div class="col-md-7">
                <div class="draft_lottery_board">
                    <h2><a class="text-white" href="{{url('league/create')}}">Create League</a></h2>

                    <!-- <div class="draft_setting">
                        <h3>Import Last Season Draft Picks, Rosters & Settings</h3>
                    </div>

                    <div class="draft_upload">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="trophy">
                                    <img src="{{ asset('images/yahoo.jpg') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="upload_desc">
                                    <h5>Drop file to upload </h5>
                                    <p>* Upload excel spreadsheet</p>
                                </div>
                                <div class="upload_sheet">
                                    <form action="/action_page.php">
                                        <input type="file" id="myFile" name="filename">
                                        <div class="upload_view">
                                            <img src="{{ asset('images/uploads.png') }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="save">
                        <button>Manually Create Draft Board</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/home/joiner.js') }}"></script>
<script type="text/javascript">
    $(function(){

        $('#inSetupLeagues').on('change', function () {
            $('#inSetupLeaguesDiv').toggle($(this).is(':checked'));
        });

        $('#startedLeagues').on('change', function () {
            $('#startedLeaguesDiv').toggle($(this).is(':checked'));
        });

        $('.btn_delete').on('click', function(e){
            e.preventDefault();
            let leagueId = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this league!",
                buttons: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: '/league/'+leagueId,
                        data: {'_method' : 'DELETE'},
                        success: function (response) {
                            if(response.status == 200){
                                successMessage('League deleted successfully!');
                                $('#leagueId-'+leagueId).remove();
                            }else{
                                errorMessage('Something went wrong. Please try again later.');
                            }
                        },
                    });
                }
            });
        })
    });
</script>
@endsection