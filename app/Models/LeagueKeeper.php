<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueKeeper extends Model
{
    use HasFactory;
    protected $fillable = ['team_id', 'player_id', 'round_number', 'created_by', 'updated_by'];

    public function player()
    {
        return $this->hasOne('App\Models\Player', 'id', 'player_id');
    }
}
