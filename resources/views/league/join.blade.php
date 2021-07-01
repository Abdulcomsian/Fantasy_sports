@extends('layouts.default')
@section('title', 'Join League')
@section('content')
<div class="create_league_table assign_order the_lottery squads_board draft_boards setting">
    <div class="container">
    	<div class="alert alert-warning alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="successMessage"></div>
    	<form id="joinLeague">
	      	<div class="row">
	        	<div class="col-md-12">
	          		<div class="side_detail">
	            		<h4>Join League</h4>
	            		<input type="hidden" name="league_id" value="{{ $league->id }}">
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-3">
	                			<h4>League Name</h4>
	              			</div>
	              			<div class="col-md-9">
	                			<h4>{{ $league->name ?? '' }}</h4>
	              			</div>
	            		</div>
	          		</div>
	          		@if(isset($league->users[0]->id))
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-12 signin">
	              				<div>
		                			<p>You are already a part of this league. Click <a href="/league/{{$league->id}}/settings">here</a> to check settings.</p>
								</div>
	              			</div>
	            		</div>
	          		</div>
	          		@elseif(Auth::check())
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-3">
	                			<h4>Select Team </h4>
	              			</div>
	              			<div class="col-md-9">
	                			<!-- <h4>17</h4> -->
	                			<div class="form-group">
	                                <select name="team_id" id="teamId">
	                                    <option value="">Select Team</option>
	                                    @if(isset($league->teams))
	                                    	@php $index = 0; @endphp
				                            @foreach($league->teams as $team)
				                            	<option value="{{$team->id}}" data-team_name="{{$team->team_name}}">Pick #{{++$index}} Team: {{$team->team_name}}</option>
				                            @endforeach
				                        @endif
	                                </select>
	                            </div>
	             			</div>
	            		</div>
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-3">
	                			<h4>Team Name</h4>
	              			</div>
	              			<div class="col-md-9">
	              				<div class="form-group">
	              					<input type="text" name="team_name" value="">
	              				</div>
	              			</div>
	            		</div>
	          		</div>
	          		<div class="save">
            			<button type="submit">Save</button>
          			</div>
	          		@else
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-12 signin">
	              				<div>
		                			<p>You are not signed in.</p>
									<p>Please <a href="/register">register</a> for an account or <a href="/login">login</a> before joining your league</p>
								</div>
	              			</div>
	            		</div>
	          		</div>
	          		@endif
	        	</div>
	    	</div>
    	</form>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/join.js') }}"></script>
@endsection