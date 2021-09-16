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
            $table->string('last_name')->nullable();
            $table->string('position');
            $table->string('team');
            $table->string('opponent')->nullable();
            $table->string('game')->nullable();
            $table->string('time')->nullable();
            $table->decimal('salary')->nullable();
            $table->string('fppg')->nullable();
            $table->string('injury_status')->nullable();
            $table->string('starting')->nullable();
            $table->string('active')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('dob')->nullable();
            $table->string('college')->nullable();
            $table->string('drafted')->nullable();            
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
