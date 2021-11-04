<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id');
            $table->foreignId('user_id')->nullable();
            $table->tinyInteger('permission_type')->default(1)->comment('1 for commish and 2 for co-commish and 3 from manager');
            $table->tinyInteger('permission_type2')->nullable();
            $table->foreignId('team_id')->nullable();
            $table->foreignId('team_id2')->nullable();
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
        Schema::dropIfExists('league_user');
    }
}
