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
        font-size: 25px;
    }
    .redText{
        color: red !important;
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
            <p></p>
        </div>
		
</div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>

@endsection