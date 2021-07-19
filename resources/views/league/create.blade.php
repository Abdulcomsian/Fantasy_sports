@extends('layouts.default')
@section('title', 'Home')
@section('content')
<div class="create_league">
    <div class="container">
        <div class="heading">
            <h1>create League</h1>
        </div>
    </div>
  
    <div class="container-fluid">
        <div class="alert alert-warning alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="createLeague">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>League Name</label>
                        <input type="text" name="name" style="width:40%" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="select_view">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group select_draft">
                            <h4>Draft Type</h4>
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item">
                                    <input type="radio" class="form-control" value="snake" name="draft_type">
                                    <label>Snake</label>
                                </li>
                                 <li class="list-inline-item">
                                    <input type="radio" class="form-control" value="linear" name="draft_type">
                                    <label>Linear</label>
                                </li>
                            </ul>
                        </div>

                        <!-- <div class="form-group select_draft league_size">
                            <h4>League Size</h4>
                            <ul class="list-unstyled list-inline">
                                @foreach(Config::get('teams') as $size)
                                    <li class="list-inline-item">
                                        <input type="radio" class="form-control" name="league_size" value="{{$size}}">
                                        <label>{{$size}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div> -->
                        <div class="select_draft draft_round">
                            <h4>League Size</h4>
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item">
                                    <!-- <label>17</label> -->
                                    <div class="form-group">
                                        <select class="lg-size" name="league_size">
                                            @foreach(Config::get('teams') as $size)
                                                <option class="text-dark" value="{{$size}}">{{$size}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>

                       
                    </div>
                    <div class="col-md-6">
                        <div class="select_draft draft_round draft-round">
                                <h4 style="width:100%">Draft Round</h4>
                                <p>(Total number of  roster positions on each team)</p>
                                <ul class="list-unstyled list-inline">
                                    <li class="list-inline-item">
                                        <!-- <label>17</label> -->
                                        <div class="form-group">
                                            <select name="draft_round">
                                                @foreach(Config::get('rounds') as $round)
                                                    <option class="text-dark" value="{{$round}}">{{$round}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <!-- <li class="list-inline-item">
                                        <button type="submit">Save & Continue</button>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    <!-- <div class="col-md-6">
                        <div class="draft_picks">
                            <h4>Import Draft Picks, Rosters </br> & League Settings</h4>
                            <div class="yahoo">
                                <img src="{{ asset('images/yahoo.jpg') }}">
                            </div>

                            <div class="skip">
                                <a href="#">Skip & Go To Draft Board</a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/create.js') }}"></script>
@endsection