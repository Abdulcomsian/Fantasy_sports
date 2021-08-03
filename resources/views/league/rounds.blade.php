@extends('layouts.default')
@section('title', 'Rounds')
@section('content')

@php
    $permissions = (isset($league->permissions[0]) && isset($league->permissions[0]->pivot) && isset($league->permissions[0]->pivot->permission_type)) ? $league->permissions[0]->pivot->permission_type : 0;
@endphp
<div class="create_league_table assign_order">
    <div class="container">
    <div class="row">
  <div class="col-md-6"></div>
			
			<div class="col-md-2  ">
				<h2 style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/draft') }}">Draft</a></h2>
			</div>
			<div class="col-md-2  ">
			<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/squads') }}">Squad<a/></h2>
			</div>
			<div class="col-md-2  ">
				<h2  style="width:100%;"><a style="color:#fff" href="{{ url('/league/'.request()->route('id').'/settings') }}">Settings</a></h2>
			</div>
    </div>
        <div class="ful-width-clm">
        <div class="heading half-clm">
            <h1 class="text-white">League Rounds</h1>
        </div>
        <div class="half-clm">
        <select class="form-control" id="roundDropdown">
            @for($index=1; $index <= $league->draft_round; $index++)
                <option value="{{Config::get('rounds')[$index]}}">{{Config::get('rounds')[$index]}}</option>
            @endfor
        </select>
    </div>
    </div>
        <br> 
        <div class="table_outer">
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
            <div class="table-responsive">
                <input type="hidden" name="league_id" value="{{$league->id}}">
                <table class="table table-striped table-editable teams sortableRoundsTable">
                    <thead>
                        <tr>
                            <th class="firstColumn">Pick</th>
                            <th class="otherColumns">Team Name</th>
                            <th class="otherColumns">Email</th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        @php
                        $pick = 1; $roundNumber = 1; $elem_no = 0;
                        @endphp
                        @foreach($league->rounds as $round)
                            @if ($round->round_number != $roundNumber)
                                @break
                            @endif
                            @if(isset($round->team))
                            @php
                                $elem_no++;
                            @endphp
                            <tr>
                                <td class="teamRound firstColumn" data-team_id="{{$round->team->id}}" data-round_id="{{$round->id}}">{{$round->round_number.'.'.$pick++}}</td>
                                
                                <td class="">
                                    <select class="teamName roundChange  form-control" data-round_id='1' data-elem_no="{{$elem_no}}">{{-- otherColumns --}}
                                        @foreach ($teams as $team)
                                            <option value="{{$team->id}}" {{($round->team->team_name == $team->team_name)?'selected':''}}>{{($team->team_name)}}</option>
                                        @endforeach
                                    </select>
                            
                                </td>
                                <td class="teamEmail otherColumns">{{$round->team->team_email}}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($permissions != 3)
        <div class="save">
            <button class="btnSave mt-4 mb-4">Save & Continue</button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<script>

    var leagueRounds = [];
    
    $(document).on('click','.roundChange',function(event){
        let round_id = parseInt(event.currentTarget.dataset.round_id);
        let elem_no = parseInt(event.currentTarget.dataset.elem_no);
        leagueRounds[round_id-1][elem_no-1]['team_id'] = parseInt(event.currentTarget.value);
        //do something
    })
    $(function() {

        // CHECKMATE->ADDED BY AWAIS Start
        $(document).ready(function () {
            $('#sortable').sortable( "disable" )
        });
        // CHECKMATE->ADDED BY AWAIS End

        let oldIndex;
        $(".teams #sortable").sortable({
            start: function(event, ui) {
                oldIndex = ui.item.index();
            },
            stop: function(event, ui) {
                let newIndex = ui.item.index();
                let movingForward = newIndex > oldIndex;            
                let nextIndex = newIndex + (movingForward ? -1 : 1);

                let items = $('.teams #sortable > tr');

                // Find the element to move
                let itemToMove = items.get(nextIndex);
                if (itemToMove) {
                    // Find the element at the index where we want to move the itemToMove
                    let newLocation = $(items.get(oldIndex));

                    // Decide if it goes before or after
                    if (movingForward) {
                        $(itemToMove).insertBefore(newLocation);
                    } else {
                        $(itemToMove).insertAfter(newLocation);
                    }
                    let newRoundOrder = [];
                    let pick = 1;
                    $('.teams #sortable tr').each(function(index, elem){
                        let round = {};
                        round.round_id = $(elem).find('.teamRound').data('round_id');
                        round.pick = pick++;
                        round.team_id = $(elem).find('.teamRound').data('team_id');
                        round.team_name = $(elem).find('.teamName').text();
                        round.team_email = $(elem).find('.teamEmail').text();
                        newRoundOrder.push(round);
                    });
                    leagueRounds[$('#roundDropdown').val()-1] = newRoundOrder;
                }
            }
        });

        $(".teams #sortable").disableSelection();

        let rounds = JSON.parse('<?php echo json_encode($league->rounds, JSON_HEX_APOS); ?>');
        let teams = JSON.parse('<?php echo json_encode($teams, JSON_HEX_APOS); ?>');
        let previousRoundNumber = 0;
        let subRounds = [];
        let pick = 1;
        let index = 0;
        $.each(rounds, function (i, elem) {
            if(elem.team){
                let round = {};
                if(i == 0){
                    previousRoundNumber = elem.round_number;
                }
                if(elem.round_number != previousRoundNumber){
                    leagueRounds[index++] = subRounds;
                    previousRoundNumber = elem.round_number;
                    subRounds = [];
                    pick = 1;
                }


                round.round_id = elem.id;
                round.pick = pick;
                round.team_id = elem.team_id;
                round.team_name = elem.team.team_name;
                round.team_email = elem.team.team_email;
                subRounds.push(round);
                pick++;

                if(i == rounds.length-1){
                    leagueRounds[index++] = subRounds;
                }
            }
        });

        $('#roundDropdown').on('change', function(){
            let roundNumber = $(this).val();
            let newRound = leagueRounds[roundNumber-1];
            $('.teams tbody tr').remove();
            let trs = '';
            let save_index = 0;
            
            // CHECKMATE->ADDED BY AWAIS Start
            if(roundNumber % 2 == 0){
                // newRound.reverse();
                // save_index = 13;
            }
            // CHECKMATE->ADDED BY AWAIS End

            $.each(newRound, function (i, elem) {
                // if(roundNumber % 2 == 0){
                    save_index++;
                // }else{
                    // save_index++;
                // }    
                trs +=  '<tr>'+
                            '<td class="teamRound" data-team_id="'+elem.team_id+'" data-round_id="'+elem.round_id+'">'+roundNumber+'.'+elem.pick+'</td>'+
                            '<td class="">';
                trs+=           '<select class="teamName roundChange form-control" data-elem_no="'+save_index+'" data-round_id="'+roundNumber+'">';
                                $.each(teams,(ind,val)=>{
                trs+=               `<option value="${val.id}" ${(elem.team_name == val.team_name)?'selected':''}>${(val.team_name)}</option>`;
                                }) 
                trs+=           '</select>';
                trs+=           '</td>';
                trs+=           '<td class="teamEmail">'+elem.team_email+'</td>';
                trs+=   '</tr>';
            });
            $('.teams tbody').append(trs);
        });

        $('.btnSave').on('click', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/league/'+$("input[name='league_id']").val()+'/rounds',
                data: {'rounds' : JSON.stringify(leagueRounds)},
                success: function (response) {
                    console.log(response);
                    if(response.status == 200){
                        successMessage(response.message);
                        $('html').scrollTop(0);
                        setTimeout(function(){ window.location.href = '/league/'+response.data.id+'/settings'; }, 1000);
                    }else{
                        errorMessage(response.message);
                    }
                },
            });
        });

        function prepareRoundData(){
            let rounds = [];
            $('.teams tbody tr').each(function(){
                let round = {};
                round.round_id = $(this).find('.teamRound').data('round_id');
                round.team_id = $(this).find('.teamRound').data('team_id');
                rounds.push(round);
            });
            return rounds;
        }
    });
</script>
@endsection
