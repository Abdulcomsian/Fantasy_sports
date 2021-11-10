@extends('layouts.default')
@section('title', 'Settings')
@section('content')

@php
$permissions = (isset($league->permissions[0]) && isset($league->permissions[0]->pivot) && isset($league->permissions[0]->pivot->permission_type)) ? $league->permissions[0]->pivot->permission_type : 0;
@endphp
<style>
	.colorPickSelector {
		border-radius: 5px;
		width: 36px;
		height: 36px;
		margin-left: 50px;
		cursor: pointer;
		-webkit-transition: all linear .2s;
		-moz-transition: all linear .2s;
		-ms-transition: all linear .2s;
		-o-transition: all linear .2s;
		transition: all linear .2s;
	}

	.colorPickSelector:hover {
		transform: scale(1.1);
	}

	.colorPicker {
		width: 220px;
	}

	.PickSelector {
		width: 32px;
		height: 32px;
	}

	input[type=color] {
		display: none;
	}

	.incrementNumber {
		margin: 5px;
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
		<div class="successMessage"></div>
		<form id="updateLeague">
			<div class="row">
				<div class="col-md-6"></div>

				<div class="col-md-3">
					<h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft Board</a></h2>
				</div>
				<!-- <div class="col-md-2">
					<p onclick="myFunction()" class="dropbtn">Select <i class="fa fa-angle-down" aria-hidden="true"></i></p>
					<div id="myDropdown" class="dropdown-content">
						<a href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad</a>
						<a href="{{url('draft-roaster')}}">Draft Roaster</a>
					</div>
					<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad<a/></h2>
				</div> -->
				<div class="col-md-3">
					<h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
				</div>
				<div class="col-md-5">
					<div class="side_detail">
						<h4>Settings</h4>
						<input type="hidden" name="league_id" value="{{ $league->id }}">
						<input type="hidden" name="user_email" value="{{Auth::user()->email}}">
					</div>
					<div class="list_edit">
						<div class="row">
							<div class="col-md-6 no-bdr">
								<h4><span><i class="fa fa-star yellow"></i>League Name</span> </h4>
							</div>
							<div class="col-md-6 f-wdth">
								<input type="text" name="name" value="{{ $league->name ?? '' }}" {{ $permissions == 3 ? "readonly" : "" }}>
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
                <h4><span><i class="fa fa-star yellow"></i>Draft Round</span></h4>
            </div>
            <div class="col-md-6">
                <div class="form-group f-wdth">
                    <input type="text" name="draft_round" value="{{$league->draft_round}}" disabled="disabled">
                </div>
            </div>
        </div>
    </div>
	
	<!-- <div class="list_edit">
		<div class="row">
			<div class="col-md-6 no-bdr">
				<h4><span><i class="fa fa-star yellow"></i>Draft Round</span></h4>
			</div>
			<div class="col-md-6">
				<div class="form-group f-wdth">
					<select name="draft_round" {{ $permissions == 3 ? "disabled" : "" }} disabled>
						@foreach(Config::get('rounds') as $round)
						<option class="text-dark" value="{{$round}}" {{$league->draft_round == $round ? 'selected' : ''}}>{{$round}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div> -->
	<div class="list_edit">
		<div class="row">
			<div class="col-md-6 no-bdr">
				<h4><span><i class="fa fa-star yellow"></i>Edit Mode</span></h4>
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
				<h4><span><i class="fa fa-star yellow"></i>Draft Mode</span></h4>
			</div>
			<div class="col-md-6">
				<div class="custom-control custom-switch">
					<input type="checkbox" class="custom-control-input lequeMode" id="draftMode" {{ $league->status == 'started' ? 'checked' : '' }} value="started">
					<label class="custom-control-label on-off" for="draftMode"></label>
				</div>
			</div>
		</div>
	</div>
	<div class="list_edit">
		<div class="row">
			<div class="col-md-6">
				<h4><span style="padding-top:10px;"><i class="fa fa-star yellow"></i>Draft Type</span></h4>
			</div>
			<div class="col-md-6">
				<button type="button" style="" class="addCommish">{{$league->draft_type}}</button>
			</div>
		</div>
	</div>
	{{--@if($permissions != 3)--}}
	<div class="list_edit">
		<div class="row">
			<div class="col-md-12 no-bdr">
				<button type="button" style="background:transparent;" class="create_new">Set Commissioners</button>
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
                    @foreach($leaguser as $user)
                    <option class="text-dark" value="{{$user->user_id}}" data-league_id="{{$league->id}}" @if($user->permission_type==1){{'selected'}}@endif>{{$user->team_name . '   | '  . $user->name}}</option>
                    @endforeach
					{{--@foreach($league->teams as $user)
					<option class="text-dark" value="{{$user->id}}" data-league_id="{{$league->id}}" @if($leaguser->team_id==$user->id && $permissions==1)selected="{{'selected'}}"@endif>{{$user->team_name}}</option>
					@endforeach--}}
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
				<select name="co_commish_user_id">
					<option value="">Select User</option>
                    @foreach($leaguser as $user)
                    <option class="text-dark" value="{{$user->user_id}}" data-league_id="{{$league->id}}" @if($user->permission_type==2){{'selected'}}@endif>{{$user->team_name. '   |    '. $user->name}}</option>
                    @endforeach
					{{--@foreach($league->teams as $user)
					<option class="text-dark" value="{{$user->id}}" data-league_id="{{$league->id}}" @if($league->permissions[0]->pivot->team_id2==$user->id)selected="{{'selected'}}"@endif>{{$user->team_name}}</option>
					@endforeach--}}
				</select>
			</div>
			<div class="col-md-3">
				<button class="addCoCommish">Add</button>
			</div>
		</div>
	</div>
	{{--@endif--}}
	<div class="row">
		<div class="col-lg-12">
			<div class="inviteUrl" style="background:transparent;">
				<p class="create_new ft-z" style="background:transparent;border:0px; padding-left:0px;margin-top:0px;padding-top:0px;">Invite URL: {{url('league/join?key='.$league->joiner_key)}}</p>
				<p>Send the URL to anyone who is a GM in your league. They will be
					able to claim ownership of their team and will only be able to
					navigate and make picks during live draft mode and edit their team
					name</p>
			</div>
		</div>
	</div>
	<!-- @if($permissions != 3)
	

	@endif -->
	<!-- <div class="list_edit">
		<div class="row">
			<div class="col-md-12 no-bdr">
				<h4 style="text-align:left;">Joiner Key</h4>
				<p class="create_new ft-z" style="background:transparent;border:0px; padding-left:0px;margin-top:0px;padding-top:0px;">{{url('league/join?key='.$league->joiner_key)}}</p>
			</div>
		</div>
	</div> -->
	<!-- <div class="list_edit">
		<div class="row">
			<div class="col-md-12">
				<div class="side_detail sd-dtl" >
					<a href="{{url('league/'.$league->id.'/rounds')}}">Enter Draft Picks Manually</a>
				</div>
			</div>
		</div>
	</div> -->
</div>
<div class="col-md-7">
	<!-- <div class="draft_tag">
		<h5>Draft Order</h5>
	</div> -->
	<div class="draft_lottery_board">
		<!-- <h2><a href="{{ url('/league/'.$league->id.'/draft') }}" style="color:#fff" class="">Back to Draft Board</a></h2> -->
		<h2 style="background:transparent;">Owners Info & Draft Order</h2>
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
		<!-- <div class="save" style="margin-bottom:21px;">
			<button type="button" class="addTeam"><a href="{{url('league/'.$league->id.'/rounds')}}" style="color:#fff;">Enter Draft Picks Manually</a></button>
		</div> -->
		<div class="save">
			<button type="submit">Save Changes</button>
		</div>
	</div>
	</form>
    <br>
	<div class="rosterSetting">
		<h5 class="text-center">Roster Setting</h5>
		<br>
		<center>
			<P>Roster Spots & Draft Rounds:<span class="draftRound">{{$league->draft_round ?? ''}}</span></P>
		</center>
		<form method="post" action="{{url('league/'.$league->id.'/save-roster')}}" id="rosterform">
			@csrf
			@foreach($rosterdata as $data)
			@php
			if($data->position=="QB")
			{
			$qbcount=$data->totalcount;
			$qbcolor=$data->color;
			}
			elseif($data->position=="RB")
			{
			$rbcount=$data->totalcount;
			$rbcolor=$data->color;
			}
			elseif($data->position=="WR")
			{
			$wrcount=$data->totalcount;
			$wrcolor=$data->color;
			}
			elseif($data->position=="TE")
			{
			$tecount=$data->totalcount;
			$tecolor=$data->color;
			}
			elseif($data->position=="WRT")
			{
			$wrtcount=$data->totalcount;
			$wrtcolor=$data->color;
			}
			elseif($data->position=='WR/TE')
			{
			$wrtecount=$data->totalcount;
			$wrtecolor=$data->color;
			}
			elseif($data->position=="WR/RB")
			{
			$wrrbcount=$data->totalcount;
			$wrrbcolor=$data->color;
			}
			elseif($data->position=="QB/WR/RB/TE")
			{
			$qbwrrbtecount=$data->totalcount;
			$qbwrrbtecolor=$data->color;
			}
			elseif($data->position=="K")
			{
			$kcount=$data->totalcount;
			$kcolor=$data->color;
			}
			elseif($data->position=="DEF")
			{
			$defcount=$data->totalcount;
			$defcolor=$data->color;
			}
			elseif($data->position=="DL")
			{
			$dlcount=$data->totalcount;
			$dlcolor=$data->color;
			}
			elseif($data->position=="LB")
			{
			$lbcount=$data->totalcount;
			$lbcolor=$data->color;
			}
			elseif($data->position=="IDP")
			{
			$idpcount=$data->totalcount;
			$idpcolor=$data->color;
			}
			elseif($data->position=="DB")
			{
			$dbcount=$data->totalcount;
			$dbcolor=$data->color;
			}
			elseif($data->position=="BENCH")
			{
			$bencount=$data->totalcount;
			$bencolor=$data->color;
			}
			@endphp
			@endforeach
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="1">-</button>
					<input type="text" name="posrow[]" value="{{$qbcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="1">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$qbcolor ?? '#000'}};color:white;border:1px solid;" data-button-id="1" class="PickSelector"></button>
					<input type="color" disabled class="favcolor" id="colorInput" data-id="1" name="favcolor[]" value="{{$qbcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="1" id="order1" />
				<input type="hidden" name="pos[]" value="QB" id="posid1" />
				<p>QUARTERBACK (QB)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="2">-</button>
					<input type="text" name="posrow[]" value="{{$rbcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="2">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$rbcolor ?? '#000'}};color:white;border:1px solid;" data-button-id="2" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="2" id="colorInput" name="favcolor[]" type="color" value="{{$rbcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="2" id="order2" />
				<input type="hidden" name="pos[]" value="RB" id="posid2" />
				<p>RUNNING BACK (RB)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="3">-</button>
					<input type="text" name="posrow[]" value="{{$wrcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="3">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$wrcolor ?? '#000'}};color:white;border:1px solid;" data-button-id="3" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="3" id="colorInput" name="favcolor[]" type="color" value="{{$wrcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="3" id="order3" />
				<input type="hidden" name="pos[]" value="WR" id="posid3" />
				<p>WIDE RECEIVER (WR)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="4">-</button>
					<input type="text" name="posrow[]" value="{{$tecount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="4">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$tecolor ?? '#000'}};color:white;border:1px solid;" data-button-id="4" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="4" id="colorInput" name="favcolor[]" type="color" value="{{$tecolor ?? ''}}">
				</div>
				<input type="hidden" name="order[]" value="4" id="order4" />
				<input type="hidden" name="pos[]" value="TE" id="posid4" />
				<p>TIGHT END (TE)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="5">-</button>
					<input type="text" name="posrow[]" value="{{$wrtcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="5">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$wrtcolor ?? '#000'}};color:white;border:1px solid;;@if(!isset($wrtcount )){{'cursor: no-drop;'}}@endif" data-button-id="5" class="PickSelector d-none"></button>
					<input class="favcolor d-none" disabled data-id="5" id="colorInput" name="favcolor[]" type="color" value="{{$wrtcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="5" id="order5" />
				<input type="hidden" name="pos[]" value="WRT" id="posid5" />
				<p>(FLEX (WR/RB/TE))</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="13">-</button>
					<input type="text" name="posrow[]" value="{{$wrtecount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="13">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$wrtecolor ?? '#000'}};color:white;border:1px solid;;@if(!isset($wrtecount)){{'cursor: no-drop;'}}@endif" data-button-id="13" class="PickSelector d-none"></button>
					<input class="favcolor d-none" disabled data-id="13" id="colorInput" name="favcolor[]" type="color" value="{{$wrtecolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="6" id="order13" />
				<input type="hidden" name="pos[]" value="WR/TE" id="posid13" />
				<p>FLEX (WR/TE)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="14">-</button>
					<input type="text" name="posrow[]" value="{{$wrrbcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="14">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$wrrbcolor ?? '#000'}};color:white;border:1px solid;;@if(!isset($wrrbcount)){{'cursor: no-drop;'}}@endif" data-button-id="14" class="PickSelector d-none"></button>
					<input class="favcolor d-none" disabled data-id="14" id="colorInput" name="favcolor[]" type="color" value="{{$wrrbcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="7" id="order14" />
				<input type="hidden" name="pos[]" value="WR/RB" id="posid14" />
				<p>FLEX (WR/RB)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="15">-</button>
					<input type="text" name="posrow[]" value="{{$qbwrrbtecount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="15">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$qbwrrbtecolor ?? '#000'}};color:white;border:1px solid;;@if(!isset($qbwrrbtecount)){{'cursor: no-drop;'}}@endif" data-button-id="15" class="PickSelector d-none"></button>
					<input class="favcolor d-none" disabled data-id="15" id="colorInput" name="favcolor[]" type="color" value="{{$qbwrrbtecolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="8" id="order15" />
				<input type="hidden" name="pos[]" value="QB/WR/RB/TE" id="posid15" />
				<p>FLEX (QB/WR/RB/TE)</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="6">-</button>
					<input type="text" name="posrow[]" value="{{$kcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="6">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$kcolor ?? '#000'}};color:white;border:1px solid;;@if(!isset($kcount)){{'cursor: no-drop;'}}@endif" data-button-id="6" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="6" id="colorInput" name="favcolor[]" type="color" value="{{$kcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="9" id="order6" />
				<input type="hidden" name="pos[]" value="K" id="posid6" />
				<p>K</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="7">-</button>
					<input type="text" name="posrow[]" value="{{$defcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="7">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="background:{{$defcolor  ?? '#000'}};color:white;border:1px solid;;@if(!isset($defcount)){{'cursor: no-drop;'}}@endif" data-button-id="7" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="7" id="colorInput" name="favcolor[]" type="color" value="{{$defcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="10" id="order7" />
				<input type="hidden" name="pos[]" value="DEF" id="posid7" />
				<p>DEF</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="12">-</button>
					<input type="text" name="posrow[]" value="{{$bencount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="12">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="@if(!isset($bencount)){{'cursor: no-drop;'}}@endif background:{{$bencolor  ?? '#000'}};color:white;border:1px solid;" data-button-id="12" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="12" id="colorInput" name="favcolor[]" type="color" value="{{$bencolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="11" id="order12" />
				<input type="hidden" name="pos[]" value="BENCH" id="posid12" />
				<p>Bench</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="8">-</button>
					<input type="text" name="posrow[]" value="{{$dlcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="8">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="@if(!isset($dlcount)){{'cursor: no-drop;'}}@endif background:{{$dlcolor  ?? '#000'}};color:white;border:1px solid;" data-button-id="8" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="8" id="colorInput" name="favcolor[]" type="color" value="{{$dlcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="12" id="order8" />
				<input type="hidden" name="pos[]" value="DL" id="posid8" />
				<p>DL</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="9">-</button>
					<input type="text" name="posrow[]" value="{{$lbcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="9">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="@if(!isset($lbcount)){{'cursor: no-drop;'}}@endif background:{{$lbcolor  ?? '#000'}};color:white;border:1px solid;" data-button-id="9" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="9" id="colorInput" name="favcolor[]" type="color" value="{{$lbcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="13" id="order9" />
				<input type="hidden" name="pos[]" value="LB" id="posid9" />
				<p>LB</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="11">-</button>
					<input type="text" name="posrow[]" value="{{$dbcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="11">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="@if(!isset($dbcount)){{'cursor: no-drop;'}}@endif background:{{$dbcolor ?? '#000'}};color:white;border:1px solid;" data-button-id="11" class="PickSelector"></button>
					<input class="favcolor" disabled data-id="11" id="colorInput" name="favcolor[]" type="color" value="{{$dbcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="14" id="order11" />
				<input type="hidden" name="pos[]" value="DB" id="posid11" />
				<p>DB</p>
			</div>
			<div class="colorPickerDiv">
				<div class="incrementNumber">
					<button type="button" class="minusBtn" data-id="10">-</button>
					<input type="text" name="posrow[]" value="{{$idpcount ?? '0'}}" min="0">
					<button type="button" class="plusBtn" data-id="10">+</button>
				</div>
				<div class="colorPicker">
					<button type="button" style="@if(!isset($idpcount)){{'cursor: no-drop;'}}@endif background:{{$idpcolor ?? '#000'}};color:white;border:1px solid;" data-button-id="10" class="PickSelector d-none"></button>
					<input class="favcolor d-none" disabled data-id="10" id="colorInput" name="favcolor[]" type="color" value="{{$idpcolor ?? '#000'}}">
				</div>
				<input type="hidden" name="order[]" value="15" id="order10" />
				<input type="hidden" name="pos[]" value="IDP" id="posid10" />
				<p>IDP</p>
			</div>
			<div>
				@if(count($rosterdata)<=0) <center><input type="submit" value="submit"></center>
					@endif
			</div>
		</form>
	</div>
</div>
</div>
<!-- </form> -->
</div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/league/settings.js') }}"></script>
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
	$(".plusBtn").click(function() {
		let roundValue=$(".draftRound").text();
		roundValue=parseInt(roundValue)+1;
		$(".draftRound").text(roundValue)
		id = $(this).attr('data-id');
		let teams = prepareTeamData();
		pos = $("#posid" + id + "").val();
		val = parseInt($(this).parent().find("input").val());
		color = $(this).parent().next().find("input").val();
		orderno = $("#order" + id + "").val();
		val = val + 1;
		$(this).parent().find("input").val(val);
		//AJAX FOR INSERT NEW ROW
		$.ajax({
			type: 'POST',
			url: "/league/" + $("input[name='league_id']").val() + "/insertrow",
			data: {
				val: val,
				pos: pos,
				color: color,
				leagueId: $("input[name='league_id']").val(),
				orderno: orderno,
				'league_id': $("input[name='league_id']").val(),
				'name': $("input[name='name']").val(),
				'draft_round': '{{$league->draft_round+1}}',
				'teams': teams
			},
			success: function(response) {
				if (response == "success") {
					successMessage('New roster inserted successfully');
                     location.reload();
				} else {
					errorMessage("Something Went Wrong");
                     location.reload();
				}
			},
		});
	})
	$(".minusBtn").click(function() {
		val = parseInt($(this).parent().find("input").val());
		val = val - 1;
		if (val < 0) {
			$(this).parent().find("input").val(0);
		} else {
			id = $(this).attr('data-id');
			let teams = prepareTeamData();
			pos = $("#posid" + id + "").val();
			color = $(this).parent().next().find("input").val();
			orderno = $("#order" + id + "").val();
			$(this).parent().find("input").val(val);
			//AJAX FOR INSERT NEW ROW
			$.ajax({
				type: 'POST',
				url: "/league/" + $("input[name='league_id']").val() + "/deleterow",
				data: {
					val: val,
					pos: pos,
					color: color,
					leagueId: $("input[name='league_id']").val(),
					orderno: orderno,
					'league_id': $("input[name='league_id']").val(),
					'name': $("input[name='name']").val(),
					'draft_round': '{{$league->draft_round-1}}',
					'teams': teams
				},
				success: function(response) {
					if (response == "success") {
						successMessage('roster Deleted  successfully');
                        location.reload();
					} else {
						errorMessage("Something Went Wrong");
                         location.reload();
					}
				},
			});
		}

	})

	// $(document).on('input', '.favcolor', function() {
	// 	$(this).attr("value", $(this).val());
	// 	color = $(this).val();
	// 	id = $(this).attr('data-id');
	// 	pos = $("#order" + id + "").next().val();
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: "/league/" + $("input[name='league_id']").val() + "/addcolor",
	// 		data: {
	// 			pos: pos,
	// 			color: color,
	// 			leagueId: $("input[name='league_id']").val(),
	// 		},
	// 		success: function(response) {
	// 			if (response == "success") {
	// 				successMessage('color for postion inserted successfully');
	// 			} else {
	// 				errorMessage("Something Went Wrong");
	// 			}
	// 		},
	// 	});
	// })

	//$(".colorPickSelector").colorPick();
	$(".PickSelector").colorPick({
		// 'initialColor': '#3498db',
		'allowRecent': true,
		'recentMax': 5,
		'allowCustomColor': false,
		'palette': ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#bdc3c7", "#95a5a6", "#7f8c8d"],
		'onColorSelected': function() {
			// this.element.css({
			// 	'backgroundColor': this.color,
			// 	'color': this.color
			// });
			if (this.color != '#000XXX') {
				id = this.element.attr('data-button-id');
				color = this.color;
				$("[data-id='" + id + "']").val(this.color);
				pos = $("#order" + id + "").next().val();

				$.ajax({
					type: 'POST',
					url: "/league/" + $("input[name='league_id']").val() + "/addcolor",
					data: {
						pos: pos,
						color: color,
						leagueId: $("input[name='league_id']").val(),
					},
					success: function(response) {
						if (response == "success") {
							successMessage('color for postion inserted successfully');
							location.reload();
						} else {
							errorMessage("Something Went Wrong");
							location.reload();
						}
					},
				});
			}

		}
	});
</script>
@endsection