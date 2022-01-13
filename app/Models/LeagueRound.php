<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueRound extends Model
{
    use HasFactory;
    protected $fillable = ['league_id', 'team_id','old_team_id','round_number', 'default_order', 'round_order', 'player_id', 'roundtype','created_by', 'updated_by'];

    public function team()
    {
        return $this->hasOne('App\Models\LeagueTeam', 'id', 'team_id');
    }

    public function player()
    {
        return $this->hasOne('App\Models\Player', 'id', 'player_id');
    }

    public function keeperCost(){
        return $this->hasOne('App\Models\LeagueKeeper', 'player_id', 'player_id');   
    }

    protected static function fetchData($leagueId, $orderBy = 'asc'){
        $leagueRoundQuery = self::with(['player', 'team'])->select('league_rounds.*')
                        ->join('league_teams', 'league_teams.id', 'league_rounds.team_id')
                        ->orderBy('league_rounds.round_number', $orderBy)
                        ->orderBy('league_rounds.id', $orderBy);
        if($orderBy == 'desc'){
            $leagueRoundQuery = $leagueRoundQuery->whereNotNull('league_rounds.player_id');
        }else{
            $leagueRoundQuery = $leagueRoundQuery->whereNull('league_rounds.player_id');   
        }
        $leagueRound = $leagueRoundQuery->where('league_rounds.league_id', $leagueId)->first();
        
        if(isset($leagueRound->id)){
            return (object)[
                'round_id' => $leagueRound->id ?? 0,
                'round_number' => $leagueRound->round_number ?? 0,
                'player_id' => $leagueRound->player_id ?? 0,
                'last_name' => (isset($leagueRound->player) && isset($leagueRound->player->last_name)) ? $leagueRound->player->last_name : '',
                'first_name' => (isset($leagueRound->player) && isset($leagueRound->player->first_name)) ? $leagueRound->player->first_name : '',
                'position' => (isset($leagueRound->player) && isset($leagueRound->player->position)) ? $leagueRound->player->position : '',
                'team_name' => (isset($leagueRound->team) && isset($leagueRound->team->team_name)) ? $leagueRound->team->team_name : '',
                'pick_number' => (isset($leagueRound->team) && isset($leagueRound->team->team_order)) ? $leagueRound->team->team_order : ''
            ];
        }else{
            return null;
        }
    }
}
