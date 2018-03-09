<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at');
            $table->unsignedInteger('server_id')->index();
            $table->string('steam_id', 25)->index();
            $table->unsignedInteger('user_id');
            $table->string('user_name', 25);
            $table->string('note', 4000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_notes');
    }
}
