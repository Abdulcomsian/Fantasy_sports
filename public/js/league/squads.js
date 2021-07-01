$(function(){
    fetchPlayers(leagueId, teamId);
    $('.players').on('keypress', 'input[type="number"]', function(evt){
        evt.preventDefault();
    });
    $('.players').on('change', 'input[name="keeperCostCheck"]', function(){
        let self = $(this);
        let roundNumber = self.closest('tr').find('input[name="round_number"]').val();
        let playerId = self.closest('td').data('player_id');
        let teamId = self.closest('td').data('team_id');
        if (this.checked) {
            saveKeeper(roundNumber, playerId, teamId, 1, self);
        }else{
            saveKeeper(roundNumber, playerId, teamId, 0, self);
        }
    });
});

function fetchPlayers(leagueId, teamId){
    
    $.ajax({
        type: 'GET',
        url: '/league/'+leagueId+'/team/'+teamId+'/players',
        success: function (response) {
            if(response.status == 200){
                $('.players').html(response.data.players_html);
                $('html').scrollTop(0);
            }else{
                errorMessage(response.message);
            }
        },
    });
}

function saveKeeper(roundNumber, playerId, teamId, status, self){
    $.ajax({
        type: 'POST',
        url: '/league/'+$("input[name='league_id']").val()+'/keeper/cost',
        data: {'round_number': roundNumber, 'player_id': playerId, 'team_id': teamId, 'status': status},
        success: function (response) {
            if(response.status == 200){
                successMessage(response.message);
                $('html').scrollTop(0);
                status = status == 0 ? false : true;
                self.closest('tr').find('input[name="round_number"]').attr("readonly", status);
            }else{
                errorMessage(response.message);
                self.prop("checked", false);
            }
        },
    });
}