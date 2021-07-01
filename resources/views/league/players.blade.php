@if($leagueRounds->count() > 0)
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
     <div class="table_cover">
       <div class="row">
        <div class="col-md-6">
          <div class="name">
            <h3>{{ isset($leagueRounds[0]) && isset($leagueRounds[0]->team) ? $leagueRounds[0]->team->team_name : '' }}</h3>
          </div>
        </div>
      </div>
      <div class="table_outer">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text_head">Player</th>
                <th>Keeper Cost</th>
                <th>2019 Draft Position</th>
                <th>Add To Keeper List</th>
              </tr>
            </thead>
            <tbody>
              @php $count = 0; @endphp
              @foreach($leagueRounds as $round)
                @if(isset($round->player) && isset($round->player->id))
                  @php $count++; @endphp
                  <tr>
                    <td class="txt_head">{{$round->player->last_name.' '.$round->player->first_name.' ('.$round->player->position.') '}}</td>
                    <td><input type="number" name="round_number" min='1' max="{{ $roundsCount }}" value="{{ $round->keeperCost && $round->keeperCost->round_number ? $round->keeperCost->round_number : 1 }}" {{ $round->keeperCost ? 'readonly' : '' }}></td>
                    <td>Round 1, Pick 1</td>
                    <td data-player_id='{{$round->player->id}}' data-team_id='{{$round->team_id}}'><input type="checkbox" name="keeperCostCheck" class="largerCheckbox" {{ $round->keeperCost ? 'checked' : '' }}></td>
                  </tr>
                @endif
              @endforeach
              @if($count == 0)
                <tr>
                  <td class="txt_head" colspan="4">No player added yet!</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endif