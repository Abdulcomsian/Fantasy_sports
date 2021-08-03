@extends('layouts.default')
@section('title', 'Teams')
@section('content')
<div class="season_fall create_league_table assign_order the_lottery squads_board">
  <div class="container">
  <div class="row">
  <div class="col-md-6"></div>
			
			<div class="col-md-2">
				<h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft</a></h2>
			</div>
			<div class="col-md-2">
			<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad<a/></h2>
			</div>
			<div class="col-md-2">
				<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
			</div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="side_detail">
          <h4>SQUADS</h4>
          <input type="hidden" name="league_id" value="{{ $league->id }}">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            @foreach($league->teams as $team)
            <li class="nav-item" onclick="fetchPlayers('{{ $league->id }}', '{{ $team->id }}')">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ $team->team_name }}</a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="col-md-9 players">
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var teamId = '{{ isset($league->teams) && isset($league->teams[0]) ? $league->teams[0]->id : 0 }}';
    var leagueId = '{{ isset($league) && isset($league->id) ? $league->id : 0 }}';
</script>
<script type="text/javascript" src="{{ asset('js/league/squads.js') }}"></script>
@endsection