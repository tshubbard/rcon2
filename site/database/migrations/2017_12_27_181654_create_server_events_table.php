<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('server_id');
            $table->string('event_type', 30);
            $table->string('command_key');
            $table->text('command');
            $table->string('command_trigger')->nullable();
            $table->text('command_text')->nullable();
            $table->string('command_itemid')->nullable();
            $table->string('command_target')->nullable();
            $table->unsignedInteger('command_quantity')->nullable();
            $table->unsignedMediumInteger('interval')->nullable();
            $table->boolean('is_indefinite')->default(0);
            $table->boolean('is_public')->default(0);
            $table->boolean('is_active')->default(0);
            $table->unsignedMediumInteger('votes')->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('lastrun_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * noticeable noticible
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_events');
    }
}
