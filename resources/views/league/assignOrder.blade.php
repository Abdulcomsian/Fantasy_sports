@extends('layouts.default')
@section('title', 'Teams')
@section('content')
<div class="create_league_table assign_order">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="side_detail">
                    <h4>create League</h4>
                    <div class="list_box">
                        <p>Designate Draft Picks Using Lottery Pick Simulator</p>
                    </div>
                    <p>
                        Draft Lottery Conditions </br>
                        - Highest Probably to win #1 overall pick goes to </br>
                        lowest rank </br>
                        -Lottery will be in descending order from highest to </br>
                        1st overall pick
                    </p>
                    <!-- <h3>or</h3>
                    <a href="{{url('league/'.$league_id.'/rounds')}}">Enter Draft Picks Manually</a> -->
                </div>
            </div>

            <div class="col-md-7">
                <h2>Assign Order</h2>
                <input type="hidden" name="league_id" value="{{$league_id}}">
                <div class="table_outer">
                    <div class="table-responsive">
                        <table class="table table-striped teams assignOrder">
                            <thead>
                                <tr>
                                    <th>Last Year’s Rank</th>
                                    <th>Team Name</th>
                                </tr>
                            </thead>
                            <tbody  id="sortable">
                                @if(isset($teams))
                                    @foreach($teams as $key => $team)
                                    <tr>
                                        <td class="teamId" data-id="{{$team->id}}">
                                            {{$key+1}}
                                        </td>
                                        <td>
                                            <div class="state"><span>=</span></div>
                                            {{$team->team_name}}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="save">
                    <button class="btnSave">Save & Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

<script>
    $(function(){
        let oldIndex;
        $("#sortable").sortable({
            start: function(event, ui) {
                oldIndex = ui.item.index();
            },
            stop: function(event, ui) {
                let newIndex = ui.item.index();
                let movingForward = newIndex > oldIndex;            
                let nextIndex = newIndex + (movingForward ? -1 : 1);

                let items = $('#sortable > tr');

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
                }
            }
        });

        $("#sortable").disableSelection();

        $('.btnSave').on('click', function(e){
            e.preventDefault();
            let teams = prepareTeamData();
            $.ajax({
                type: 'POST',
                url: '/league/'+$("input[name='league_id']").val()+'/order',
                data: {'teams' : teams},
                success: function (response) {
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

        function prepareTeamData(){
            let teams = [];
            $('.teams tbody tr').each(function(){
                
                let team = {};
                team.team_id = $(this).find('.teamId').data('id');
                teams.push(team);
            });
            return teams;
        }
    });
</script>
@endsection