@extends('layouts.default')
@section('title', 'Settings')
@section('content')

@php
	$permissions = (isset($league->permissions[0]) && isset($league->permissions[0]->pivot) && isset($league->permissions[0]->pivot->permission_type)) ? $league->permissions[0]->pivot->permission_type : 0;
@endphp
<div class="create_league_table assign_order the_lottery squads_board draft_boards setting create_league">
    <div class="container">
    	<div class="alert alert-warning alert-dismissible hide" role="alert">
            <span class="message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="successMessage"></div>
    	<form id="updateLeague">
	      	<div class="row">
	        	<div class="col-md-5">
	          		<div class="side_detail">
	            		<h4>Settings</h4>
	            		<input type="hidden" name="league_id" value="{{ $league->id }}">
	            		<input type="hidden" name="user_email" value="{{Auth::user()->email}}">
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-6 no-bdr">
	                			<h4>League Name</h4>
	              			</div>
	              			<div class="col-md-6 f-wdth">
	              				<input  type="text" name="name" value="{{ $league->name ?? '' }}" {{ $permissions == 3 ? "readonly" : "" }}>
	                			<!-- <h4>League Name</h4> -->
	              			</div>
	            		</div>
	          		</div>
                   {{--<div class="list_edit select_view">
	            		<div class="row">
	              			<div class="col-md-6">
	                			<h4>Draft Type</h4>
	              			</div>
	              			<div class="col-md-6 select_draft">
	                			<ul class="list-unstyled list-inline">
	                                <li class="list-inline-item">
	                                    <input type="radio" class="form-control" value="snake" name="draft_type" {{ $league->draft_type == 'snake' ? 'checked' : '' }}>
	                                    <label>Snake</label>
	                                </li>
	                                 <li class="list-inline-item">
	                                    <input type="radio" class="form-control" value="linear" name="draft_type" {{ $league->draft_type == 'linear' ? 'checked' : '' }}>
	                                    <label>Linear</label>
	                                </li>
	                            </ul>
	              			</div>
	            		</div>
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-6">
	                			<h4>League Size</h4>
	              			</div>
	              			<div class="col-md-6">
	                			<div class="form-group f-wdth">
	                                <select name="league_size">
	                                    @foreach(Config::get('teams') as $size)
	                                        <option value="{{$size}}" {{count($league->teams) == $size ? 'selected' : ''}}>{{$size}}</option>
	                                    @endforeach
	                                </select>
	                            </div>
	              			</div>
	            		</div>
	          		</div>--}}
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-6 no-bdr">
	                			<h4>Draft Round </h4>
	              			</div>
	              			<div class="col-md-6">
	                			<!-- <h4>17</h4> -->
	                			<div class="form-group f-wdth">
	                                <select name="draft_round" {{ $permissions == 3 ? "disabled" : "" }}>
	                                    @foreach(Config::get('rounds') as $round)
	                                        <option class="text-dark" value="{{$round}}" {{$league->draft_round == $round ? 'selected' : ''}}>{{$round}}</option>
	                                    @endforeach
	                                </select>
	                            </div>
	             			</div>
	            		</div>
	          		</div>
	          		<div class="list_edit">
	          			<div class="row">
	          				<div class="col-md-6 no-bdr">
	                			<h4>Keeper Mode </h4>
	              			</div>
	          				<div class="col-md-6">
	          					<div class="custom-control custom-switch">
								  <input type="checkbox" class="custom-control-input lequeMode" id="keeperMode" {{ $league->status == 'keeper' ? 'checked' : '' }} value="keeper">
								  <label class="custom-control-label on-off" for="keeperMode"></label>
								</div>
	          				</div>
	          			</div>
	          		</div>
	          		<div class="list_edit">
	          			<div class="row">
	          				<div class="col-md-6 no-bdr">
	                			<h4>Draft Mode </h4>
	              			</div>
	          				<div class="col-md-6">
	          					<div class="custom-control custom-switch">
								  <input type="checkbox" class="custom-control-input lequeMode" id="draftMode" {{ $league->status == 'started' ? 'checked' : '' }} value="started">
								  <label class="custom-control-label on-off" for="draftMode"></label>
								</div>
	          				</div>
	          			</div>
	          		</div>
	          		@if($permissions != 3)
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-12 no-bdr">
	                			<button type="button" class="create_new">Set Commissioners</button>
	              			</div>
	            		</div>
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-4">
	                			<span><i class="fa fa-star yellow"></i> Commish</span>
	              			</div>
	              			<div class="col-md-5 f-wdth">
	              				<select name="commish_user_id">
	              					<option value="">Select User</option>
                                   	@foreach($league->users as $user)
                                        <option class="text-dark" value="{{$user->id}}" data-league_id="{{$league->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
	              			</div>
	              			<div class="col-md-3">
	                			<button class="addCommish">Add</button>
	              			</div>
	            		</div>
	          		</div>

	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-4">
	                			<span><i class="fa fa-star purple"></i> Co Commish</span>
	              			</div>
	              			<div class="col-md-5 f-wdth">
	                			<select  name="co_commish_user_id">
	                				<option  value="">Select User</option>
                                    @foreach($league->users as $user)
                                        <option class="text-dark"  value="{{$user->id}}" data-league_id="{{$league->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
	              			</div>
	              			<div class="col-md-3">
	                			<button class="addCoCommish">Add</button>
	              			</div>
	            		</div>
	          		</div>
	          		@endif
	          		<div class="list_edit">
	            		<div class="row">
	              			<div class="col-md-12 no-bdr">
	              				<h4>Joiner Key</h4>
	                			<p class="create_new ft-z">{{url('league/join?key='.$league->joiner_key)}}</p>
	              			</div>
	            		</div>
	          		</div>
	          		<div class="list_edit">
	            		<div class="row">
			          		<div class="col-md-12">
				                <div class="side_detail sd-dtl">
				                    <a href="{{url('league/'.$league->id.'/rounds')}}">Enter Draft Picks Manually</a>
				                </div>
				            </div>
				        </div>
				    </div>
	        	</div>
	        	<div class="col-md-7">
	          		<div class="draft_tag">
	            		<h5>Draft Order</h5>
	          		</div>
	          		<div class="draft_lottery_board">
	           			<h2>Owners Info & Draft Order</h2>
	            		<div class="table_cover">
	              			<div class="table_outer">
	                			<div class="table-responsive">
	                  				<table class="table table-striped table-editable teams">
	                    				<thead>
	                    					<tr>
	                      						<th class="text_head">Overall Pick</th>
						                      	<th>Team</th>
						                      	<th>Email</th>
						                      	@if($league->status == 'setup')
						                      	<th>Actions</th>
						                      	@endif
	                    					</tr>
	                    				</thead>
	                    				<tbody>
	                    					@if(isset($league->teams))
	                    						@php $index = 0; @endphp
					                            @foreach($league->teams as $team)
					                            @php
					                            	$editable = 'true';
					                            	if($permissions == 3){
					                            		if($team->id != $league->permissions[0]->pivot->team_id){
					                            			$editable = 'false';
					                            		}
					                            	}
					                            @endphp
					                            <tr>
					                            	<td class="txt_head teamId" data-id="{{$team->id}}">{{++$index}}</td>
					                                <td class="teamName" contenteditable="{{ $editable }}">{{$team->team_name}}</td>
					                                <td class="teamEmail" contenteditable="{{ $editable }}">{{$team->team_email}}</td>
					                                @if($league->status == 'setup')
					                                <td class="deleteTeam">
					                                	@if($permissions != 3)
					                                	<i class="fa fa-trash" aria-hidden="true"></i>
					                                	@endif
					                                </td>
					                                @endif
					                            </tr>
					                            @endforeach
					                        @endif
	                    				</tbody>
	                  				</table>
	                			</div>
	              			</div>
	            		</div>
	            		@if($league->status == 'setup')
	          			<div class="save">
	            			<button type="button" class="addTeam">Add Team</button>
	          			</div>
	          			@endif
	          			<div class="save">
	            			<button type="submit">Save Changes</button>
	          			</div>
	        		</div>
	      		</div>
	    	</div>
    	</form>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>
@endsection