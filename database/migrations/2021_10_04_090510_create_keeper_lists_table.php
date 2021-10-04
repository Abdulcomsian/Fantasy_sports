<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeeperListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keeper_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->string('player_id')->nullable();
            $table->bigInteger('round_number')->nullable();
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
        Schema::dropIfExists('keeper_lists');
    }
}
