<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class League extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'draft_type', 'draft_round', 'joiner_key', 'timer_value', 'remaining_timer', 'draft_timer', 'status', 'created_by', 'updated_by'];

    /**
     * Get the teams for the league.
     */
    public function teams()
    {
        return $this->hasMany('App\Models\LeagueTeam')->orderBy('team_order');
    }

    /**
     * Get the rounds for the league.
     */
    public function rounds()
    {
        return $this->hasMany('App\Models\LeagueRound')->orderBy('round_number');/*->orderBy('round_number')->orderBy('round_order');*/
    }

    /**
     * Get the permissions for the league.
     */
    public function permissions()
    {
        return $this->users()->where('users.id', Auth::id());
        //return $this->hasMany('App\Models\LeaguePermission');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('permission_type', 'team_id')->withTimestamps();
    }

    public function scopeLeaguePermissions($query)
    {
        return $query->with(['users as sss' => function($q){
            $q->where('users.id', Auth::id());
        }]);
    }

    protected static function fetchLeagueInfoByKey($key){
        return self::with(['teams', 'users' => function($q){
                        $q->where('user_id', Auth::id());
                    }])
                    ->where('joiner_key', $key)
                    ->first();
    }

    protected static function saveLeagueRounds($league, $key=1, $type = ''){
        if($type == 'delete' && isset($league->rounds)){
            $league->rounds()->delete();
        }
        if(isset($league->teams) && isset($league->draft_round)){
            $teams = $league->teams()->get()->toArray();
            $authUser = Auth::user();
            $rounds = [];
            for ($key; $key <= $league->draft_round; $key++) {
                if($key%2 == 0 && $league->draft_type == 'snake'){
                    $finalRounds = array_reverse($teams);
                }else{
                    $finalRounds = $teams;
                }
                
                foreach ($finalRounds as $key1 => $team) {
                    $round = [
                        'league_id' => $league->id,
                        'team_id' => $team['id'],
                        'old_team_id'=>$team['id'],
                        'round_number' => $key,
                        'default_order' => $key1+1,
                        'round_order' => $key1+1,
                        'created_by' => $authUser->id,
                    ];    
                    $rounds[] = $round;
                }
            }
            $league->rounds()->createMany($rounds); 
            return true;
        }
        return false;
    }

    protected static function addNewTeamLeagueRounds($league, $teamSize, $teamId){
        if(isset($league->teams) && isset($league->draft_round)){
            $authUser = Auth::user();
            $rounds = [];
            for ($key = 1; $key <= $league->draft_round; $key++) {
                $rounds[] = [
                    'league_id' => $league->id,
                    'team_id' => $teamId,
                    'round_number' => $key,
                    'default_order' => $teamSize,
                    'round_order' => $teamSize,
                    'created_by' => $authUser->id,
                ];       
            }
            $league->rounds()->createMany($rounds); 
            return true;
        }
        return false;
    }

    protected static function deleteLeagueRounds($league, $draftRound){
        if(isset($league->rounds)){
            $league->rounds()->where('round_number', '>', $draftRound)->delete(); 
            return true;
        }
        return false;
    }

    protected static function leagueData($leagueId, $type = ''){
                    $league = self::with(['teams.keepers.player'/*,'rounds.team', 'rounds.player'*/, 
                        'permissions', 
                        'rounds' => function($q) use ($type){
                            $q->select('league_rounds.*');
                            $q->with(['team', 'player', 'keeperCost']);
                            $q->when($type == 'rounds', function($q){
                                $q->orderBy('round_order');
                            });
                            $q->when($type == '', function($q){
                                $q->join('league_teams', 'league_rounds.team_id', '=', 'league_teams.id');
                                $q->orderBy('round_number');
                                $q->orderBy('league_teams.team_order');
                            });
                        }])
                        ->withCount([
                           'rounds as without_player_count' => function ($query) {
                            $query->whereNull('player_id');
                        }])
                        ->withCount('rounds')->findOrFail($leagueId);
        if(!isset($league->rounds) || $league->rounds->count() <= 0){
            League::saveLeagueRounds($league);
            return self::leagueData($league->id);
        }
        return $league;
    }

    protected static function getLeagueRoundsCount($leagueId){
        $league = self::leagueData($leagueId);
        if(isset($league->without_player_count) && isset($league->rounds_count)){
            return ['without_player_count' => $league->without_player_count, 'rounds_count' => $league->rounds_count];
        }
        return [];
    }
}
