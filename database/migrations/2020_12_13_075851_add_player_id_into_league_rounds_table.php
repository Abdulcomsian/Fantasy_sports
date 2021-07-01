<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayerIdIntoLeagueRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('league_rounds', function (Blueprint $table) {
            $table->foreignId('player_id')->nullable()->after('round_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('league_rounds', function (Blueprint $table) {
            $table->dropColumn('player_id');
        });
    }
}
