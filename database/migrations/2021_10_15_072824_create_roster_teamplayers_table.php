<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRosterTeamplayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_teamplayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rosters_id')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->string('player_id')->nullable();
            $table->integer('round_number')->nullable();
            $table->foreignId('league_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roster_teamplayers');
    }
}
