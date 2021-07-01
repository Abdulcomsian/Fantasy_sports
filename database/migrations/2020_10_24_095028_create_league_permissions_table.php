<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id');
            $table->foreignId('team_id')->nullable();
            $table->tinyInteger('permission_type')->default(1)->comment('1 for commish and 2 for co-commish');
            $table->foreignId('created_by');
            $table->foreignId('updated_by')->nullable();
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
        Schema::dropIfExists('league_permissions');
    }
}
