<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('player_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->string('team');
            $table->string('opponent');
            $table->string('game');
            $table->string('time');
            $table->decimal('salary');
            $table->string('fppg');
            $table->string('injury_status');
            $table->string('starting');
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
        Schema::dropIfExists('players');
    }
}
