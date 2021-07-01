<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamOrderColumnIntoLeagueTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('league_teams', function (Blueprint $table) {
            $table->tinyInteger('team_order')->after('team_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('league_teams', function (Blueprint $table) {
            $table->dropColumn('team_order');
        });
    }
}
