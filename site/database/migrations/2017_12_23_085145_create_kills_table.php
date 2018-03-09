<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id')->index();
            $table->timestamp('created_at');
            $table->string('victim_steam_id', 25)->index()->nullable();
            $table->string('victim_username', 50)->index()->nullable();
            $table->string('killer_steam_id', 25)->index()->nullable();
            $table->string('killer_username', 50)->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kills');
    }
}
