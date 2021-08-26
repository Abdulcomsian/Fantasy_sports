$(function () {
  $('.draftPlayer').select2();
  $('.keeperPlayer').select2({
    dropdownParent: $('#keeperModal')
  });

  $('.draftPlayer').on('change', function () {

    savePick($(this).val());
  });
  //my work here obaid
  $(document).on('change', "#teamselect", function () {
    $teamdata = $(this).val();
    $.ajax({
      type: 'get',
      url: '/changeTeam',
      data: { 'teamdata': $teamdata },
      success: function (response) {
        console.log(response);
        window.location = '/league/' + $("input[name='league_id']").val() + '/draft';
      }
    });

  })

  $('.undoPick').on('click', '#undoBtn', async function () {
    let lastPick = $('.undoPick #undoBtn');
    let confirmation = await confirmationDiv(lastPick);
    swal({
      title: "Are you sure?",
      text: "Once deleted, you will not be able to recover this pick!",
      content: confirmation,
      buttons: true,
    })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type: 'POST',
            url: '/league/' + leagueId + '/draft/pick/delete/' + lastPick.attr('round_id'),
            success: function (response) {
              if (response.status == 200) {
                successMessage('Pick deleted successfully!');
                $("td[data-round_id='" + lastPick.attr('round_id') + "']").text($("td[data-round_id='" + lastPick.attr('round_id') + "']").attr('data-default_order'));
                if (response.data.league_round == null) {
                  $('.undoPick').addClass('hide');
                } else {
                  let playerName = lastPick.attr('last_name') + ' ' + lastPick.attr('first_name') + ' ' + lastPick.attr('position');
                  let newOption = new Option(playerName, lastPick.attr('player_id'), false, false);
                  $('.draftPlayer').append(newOption).trigger('change');
                  updateLastPick(response.data.league_round, response.data.counts);
                  if ($('.draftPlayerLi').hasClass('hide')) {
                    $('.draftPlayerLi').removeClass('hide');
                  }
                }
              } else {
                errorMessage('Something went wrong. Please try again later.');
              }
            },
          });
        }
      });
  });

  $('.addKeeper').on('click', function () {
    $('input[name="round_id"]').val($(this).parent('td').data('round_id'));
    $('#keeperModal').modal('toggle');
  });

  $('#saveKeeper').on('click', function () {
    savePick($(".keeperPlayer option:selected").val(), $('input[name="round_id"]').val(), 'keeper');
  });

  $('.draftStatus').on('click', function (e) {
    e.preventDefault();
    let status = $(this).data('type') == 'keeper' ? 'started' : 'keeper';
    changeLeagueStatus(status);
  })
});

function changeLeagueStatus(status) {
  $.ajax({
    type: 'POST',
    url: '/league/' + $("input[name='league_id']").val() + '/changeStatus',
    data: { 'status': status },
    success: function (response) {
      window.location = '/league/' + $("input[name='league_id']").val() + '/draft';
    }
  });
}

function savePick(playerId, roundId = 0, type = 'draft') {
  let selectClass = type == 'draft' ? 'draftPlayer' : 'keeperPlayer';
  let playerLastName = $("." + selectClass + " option:selected").data('last_name');
  let playerFirstName = $("." + selectClass + " option:selected").data('first_name');
  let position = $("." + selectClass + " option:selected").data('position');
  let player_team = $("." + selectClass + " option:selected").data('team');
  if (playerId) {
    $.ajax({
      type: 'POST',
      url: '/league/' + leagueId + '/savePick',
      data: { 'player_id': playerId, 'round_id': roundId },
      success: function (response) {
        console.log(response);
        if (response.status == 200) {
          successMessage(response.message);
          $('.select2Drp').select2('val', '');
          $(".select2Drp option[value='" + playerId + "']").remove();
          // var team_name = $("td[data-round_id='"+response.data.round_id+"']").children()[0].textContent;
          //obaid work here
          var team = '';
          for (var i = 0; i < response.data.leagueteam.teams.length; i++) {
            if (response.data.leagueteam.teams[i].team_name == response.data.league_round.team_name) {
              selected = 'selected';
            }
            else {
              selected = '';
            }
            team += '<option value=' + response.data.leagueteam.teams[i].id + '|' + response.data.league_round.round_number + '|' + response.data.leagueid + '|' + playerId + ' ' + selected + '>' + response.data.leagueteam.teams[i].team_name + '</option>'
          }

          $("td[data-round_id='" + response.data.round_id + "']").children()[1].innerHTML = ('<select id="teamselect" name="teamselect">' + team + '</select><br> <span style="font-size:15px;">' + position + '</span ><span style="font-size:15px;"> ' + playerFirstName + ' </span><span style="font-size:15px;">' + player_team + '</span>' + '</span><br><span style="font-weight:bold;font-size:18px;">' + playerLastName + '</span>');
          if (type == 'draft') {
            //$("td[data-round_id='"+response.data.round_id+"']").text(playerLastName);
            if ($('.undoPick').hasClass('hide')) {
              $('.undoPick').removeClass('hide');
            }
            let leagueRound = response.data.league_round;
            leagueRound.first_name = playerFirstName;
            leagueRound.last_name = playerLastName;
            leagueRound.position = position;
            updateLastPick(leagueRound, response.data.counts);
            if (response.data.counts && response.data.counts.without_player_count == 0) {
              $('.draftPlayerLi').addClass('hide');
            }
          } else if (type == 'keeper') {
            //$("td[data-round_id='"+response.data.round_id+"']").text(playerLastName);
            $('#keeperModal').modal('toggle');
            $('input[name="round_id"]').val(0);
          }
          document.getElementById('playerBeep').play();
        } else {
          errorMessage(response.message);
        }
      },
    });
  }
}

function confirmationDiv(lastPick) {
  var div = document.createElement("div");
  div.innerHTML = '<table class="table table-striped">' +
    '<tbody>' +
    '<tr>' +
    '<td>Off Season Team</td>' +
    '<td>' + lastPick.attr('team_name') + '</td>' +
    '</tr>' +
    '<tr>' +
    '<td>Player</td>' +
    '<td>' + lastPick.attr('last_name') + ' ' + lastPick.attr('first_name') + ' ' + lastPick.attr('position') + '</td>' +
    '</tr>' +
    '<tr>' +
    '<td>Overall Pick</td>' +
    '<td>' + lastPick.attr('overall_pick') + '</td>' +
    '</tr>' +
    '<tr>' +
    '<td>Round</td>' +
    '<td>' + lastPick.attr('round_number') + '</td>' +
    '</tr>' +
    '<tr>' +
    '<td>Pick</td>' +
    '<td>' + lastPick.attr('pick_number') + '</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>';
  return div;
}

function updateLastPick(leagueRound, counts) {
  $('.undoPick #lastName').text(leagueRound.last_name);
  $('.undoPick #firstName').text(leagueRound.first_name);
  $('.undoPick #playerPosition').text(leagueRound.position);
  $('.undoPick #undoBtn')
    .attr('last_name', leagueRound.last_name)
    .attr('first_name', leagueRound.first_name)
    .attr('position', leagueRound.position)
    .attr('player_id', leagueRound.player_id)
    .attr('round_id', leagueRound.round_id)
    .attr('team_name', leagueRound.team_name)
    .attr('round_number', leagueRound.round_number)
    .attr('pick_number', leagueRound.pick_number)
    .attr('overall_pick', counts.rounds_count - counts.without_player_count);
}