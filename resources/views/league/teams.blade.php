@extends('layouts.default')
@section('title', 'Teams')
@section('content')
<div class="create_league create_league_table">
    <div class="container">
        <div class="heading">
            <h1>create League</h1>
        </div>
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
                <input type="hidden" name="league_id" value="{{$league_id}}">
                <table class="table table-striped table-editable teams">
                    <thead>
                        <tr>
                            <th>Team Name</th>
                            <th>Email</th>
                            <th>Commish Co-Commish</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($teams))
                            @foreach($teams as $team)
                            <tr>
                                <td class="teamName" contenteditable="true">{{$team->team_name}}</td>
                                <td class="teamEmail" contenteditable="true" email=true>{{$team->team_email}}</td>
                                <td class="teamPermission"><input type="checkbox" class="largerCheckbox">&nbsp;<input type="checkbox" class="largerCheckbox"></td>
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
@endsection

@section('js')
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });   

        $('.btnSave').on('click', function(e){
            e.preventDefault();
            let teams = prepareData();
            $.ajax({
                type: 'POST',
                url: '/league/'+$("input[name='league_id']").val()+'/teams',
                data: {'teams' : teams},
                success: function (response) {
                    if(response.status == 200){
                        $('.alert-success').removeClass('hide').find('.message').text(response.message);
                        $('.alert-warning').addClass('hide').find('.message').text
                        $('html').scrollTop(0);
                        setTimeout(function(){ window.location.href = '/league/'+response.data.id+'/settings'; }, 1000);
                    }else{
                        $('.alert-warning').removeClass('hide').find('.message').text(response.message);
                        $('.alert-success').addClass('hide').find('.message').text('');
                    }
                },
            });
        });

        function prepareData(){
            let teams = [];
            $('.teams tbody tr').each(function(){
                
                let team = {};
                team.team_name = $(this).find('.teamName').text().trim();
                team.team_email = $(this).find('.teamEmail').text().trim();
                team.team_permission = $(this).find('.teamPermission').find('input').val();
                teams.push(team);
            });
            return teams;
        }
    });
</script>
@endsection