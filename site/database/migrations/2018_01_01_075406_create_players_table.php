<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->unsignedInteger('server_id')->index();
            $table->timestamp('last_login')->useCurrent();
            $table->string('steam_id', 25)->unique();
            $table->string('short_id', 15)->nullable()->index();
            $table->string('username', 150)->index();
            $table->timestamp('last_steam_sync')->nullable();
            $table->string('steam_profile', 300)->nullable();
            $table->string('steam_avatar_small', 300)->nullable();
            $table->string('steam_avatar_medium', 300)->nullable();
            $table->string('steam_avatar_large', 300)->nullable();
            $table->unsignedTinyInteger('steam_vacban_count')->nullable();
            $table->unsignedTinyInteger('steam_gameban_count')->nullable();
            $table->unsignedSmallInteger('steam_days_since_last_ban')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
