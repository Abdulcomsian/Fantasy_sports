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
			<div class="col-md-6"></div>
			
			<div class="col-md-2">
				<h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft Board</a></h2>
			</div>
			<div class="col-md-2">
				<p onclick="myFunction()" class="dropbtn">Select <i class="fa fa-angle-down" aria-hidden="true"></i></p>
				<div id="myDropdown" class="dropdown-content">
					<a href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad</a>
					<a href="{{url('draft-roaster')}}">Draft Roaster</a>
				</div>
			<!-- <h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad<a/></h2> -->
			</div>
			<div class="col-md-2">
				<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
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
			<h4 ><span style="padding-top:10px;"><i class="fa fa-star yellow"></i>Draft Type</span></h4>
			</div>
			<div class="col-md-6">
				<button type="button" style="" class="addCommish">{{$league->draft_type}}</button>
			</div>
		</div>
	</div>
	@if($permissions != 3)
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
					@foreach($league->teams as $user)
					<option class="text-dark" value="{{$user->id}}" data-league_id="{{$league->id}}"  @if($leaguser->team_id==$user->id)selected="{{'selected'}}"@endif>{{$user->team_name}}</option>
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
				<select name="co_commish_user_id">
					<option value="">Select User</option>
					@foreach($league->teams as $user)
					<option class="text-dark" value="{{$user->id}}" data-league_id="{{$league->id}}" @if($leaguser->team_id==$user->id)selected="{{'selected'}}"@endif>{{$user->team_name}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-3">
				<button class="addCoCommish">Add</button>
			</div>
		</div>
	</div>
	@endif
	<div class="row">
		<div class="col-lg-12">
			<div class="inviteUrl" style="background:transparent;">
				<p>Invite URL: <a href="">http://3.129.43.224/league/7/draft</a></p>
				<p>Send the URL to anyone who is a GM in your league. They will be
					able to claim ownership of their team and will only be able to
					navigate and make picks during live draft mode and edit their team
					name</p>
			</div>
		</div>
	</div>
	<!-- @if($permissions != 3)
	

	@endif -->
	<div class="list_edit" >
		<div class="row">
			<div class="col-md-12 no-bdr">
				<h4 style="text-align:left;">Joiner Key</h4>
				<p class="create_new ft-z" style="background:transparent;border:0px; padding-left:0px;margin-top:0px;padding-top:0px;">{{url('league/join?key='.$league->joiner_key)}}</p>
			</div>
		</div>
	</div>
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
		<div class="save" style="margin-bottom:21px;">
		<button type="button" class="addTeam"><a href="{{url('league/'.$league->id.'/rounds')}}" style="color:#fff;">Enter Draft Picks Manually</a></button>
		</div>
		<div class="save">
			<button type="submit">Save Changes</button>
		</div>
	</div>
	<div class="rosterSetting">
		<h5>Roster Setting</h5>
		<p>Set Roster Position</p>
		<div class="colorPickerDiv">
			<div class="incrementNumber">
				<button type="button" class="minusBtn">-</button>
				<input type="text" value="0" min="0">
				<button type="button" class="plusBtn">+</button>
			</div>
			<div class="colorPicker">
				<input class="favcolor" id="colorInput" name="favcolor" type="color" value="">
			</div>
			<p>QUARTERBACK (QB)</p>
		</div>
		<div class="colorPickerDiv">
			<div class="incrementNumber">
				<button type="button" class="minusBtn">-</button>
				<input type="text" value="0" min="0">
				<button type="button" class="plusBtn">+</button>
			</div>
			<div class="colorPicker">
				<input class="favcolor" id="colorInput" name="favcolor" type="color" value="">
			</div>
			<p>RUNNING BACK (RB)</p>
		</div>
		<div class="colorPickerDiv">
			<div class="incrementNumber">
				<button type="button" class="minusBtn">-</button>
				<input type="text" value="0" min="0">
				<button type="button" class="plusBtn">+</button>
			</div>
			<div class="colorPicker">
				<input class="favcolor" id="colorInput" name="favcolor" type="color" value="">
			</div>
			<p>WIDE RECEIVER (WR)</p>
		</div>
		<div class="colorPickerDiv">
			<div class="incrementNumber">
				<button type="button" class="minusBtn">-</button>
				<input type="text" value="0" min="0">
				<button type="button" class="plusBtn">+</button>
			</div>
			<div class="colorPicker">
				<input class="favcolor" id="colorInput" name="favcolor" type="color" value="">
			</div>
			<p>TIGHT END (TE)</p>
		</div>
		<div class="colorPickerDiv">
			<div class="incrementNumber">
				<button type="button" class="minusBtn">-</button>
				<input type="text" value="0" min="0">
				<button type="button" class="plusBtn">+</button>
			</div>
			<div class="colorPicker">
				<input class="favcolor" id="colorInput" name="favcolor" type="color" value="">
			</div>
			<p>(FLEX(W/R/T))</p>
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
var val=0;
$(".plusBtn").click(function(){
	val=val+1;
	$(this).parent().find("input").attr("value",val);
})
$(".minusBtn").click(function(){
	val=val-1;
	if(val<0)
	{
		$(this).parent().find("input").val(0);
	} else{
		$(this).parent().find("input").val(val);
	}
	
})
let colorInput = document.getElementById('colorInput');


colorInput.addEventListener('input', () =>{
$(".favcolor").attr("value",colorInput.value)
});
</script>
@endsection