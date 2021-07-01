<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDefaultOrderIntoLeagueRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('league_rounds', function (Blueprint $table) {
            $table->tinyInteger('default_order')->after('round_number')->comment('default round order according to the team order');
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
            $table->dropColumn('default_order');
        });
    }
}
