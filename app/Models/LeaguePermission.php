<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaguePermission extends Model
{
    use HasFactory;
    protected $fillable = ['league_id', 'permission_type', 'created_by', 'updated_by'];
}
