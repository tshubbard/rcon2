<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersCurrentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players_current', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id')->index();
            $table->string('steam_id', 25)->index();

            $table->engine = 'MEMORY';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players_current');
    }
}
