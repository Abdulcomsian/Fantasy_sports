<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueTeam extends Model
{
    use HasFactory;
    protected $fillable = ['league_id', 'team_name', 'team_email', 'team_order', 'created_by', 'updated_by'];

    /**
     * Get the teams for the league.
     */
    public function keepers()
    {
        return $this->hasMany('App\Models\LeagueKeeper', 'team_id');
    }
}
