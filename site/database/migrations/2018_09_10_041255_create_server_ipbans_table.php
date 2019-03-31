<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerIpbansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_ipbans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id')->index();
            $table->unsignedInteger('created_by_user_id');
            $table->string('name');
            $table->string('ipaddress', 39)->index();
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
        Schema::dropIfExists('server_ipbans');
    }
}
