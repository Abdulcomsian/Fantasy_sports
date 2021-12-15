@extends('layouts.default')
@section('title', 'Settings')
@section('content')
<style>
    .mainContent{
        text-align: center;
    }
    .mainContent h3{
        color: #fff !important;
        font-size: 50px;
font-weight: 700;
    }
    .mainContent p{
        color: #fff;
        font-size: 18px;
    }
    .redText{
        color: red !important;
        font-weight: 700;
    }
</style>

<div class="create_league_table assign_order the_lottery squads_board draft_boards setting create_league">
	<div class="container">
		<div class="alert alert-warning alert-dismissible hide" role="alert">
			<span class="message"></span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
        <div class="mainContent">
            <h3>LEAGUE PREVIEW</h3>
            <p>Welcome to the OFFSEASON GM!</p>
            <p class="redText">A PLACE WHERE THE FANTASY SEASON NEVER ENDS!</p>
            <p>This is the ultimate site designed specifically for commissioners & owners that play in highly active fantasy leagues! From the start of the offseason, up until draft day, we got you covered! THE OFFSEASON GM helps keeper leagues organize & enhance the off season action for both commissioners, and team owners by creating an interactive draft board and league experience!</p>
            <p>Fantasy commissioners are always on the clock, and we are here to unlock and fill in the gap of what other major platforms are missing for better off season preparation!</p>
            <p>Year after year -As the commish, and member of serious and competitive leagues, I have begrudgingly managed and tracked multiple spreadsheets of traded draft picks and keepers, through multiple sports, group chats, leagues & platforms. Keeper leagues that have unique and customized keeper rules and involvement. This has become a manually intensive process, riddled with human error that can cause serious live offline draft day issues! This platform will provide higher league participation, functionality, and organization by making you the best commish and GM in all of fantasy!</p>
            <p class="redText">NOW GO WIN THE SEASON -- IN THE OFF SEASON!</p>
        </div>

		
</div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>

@endsection