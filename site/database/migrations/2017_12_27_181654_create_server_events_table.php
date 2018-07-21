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
            $table->unsignedInteger('created_by_user_id');
            $table->text('description')->nullable();
            $table->string('event_type', 30);
            $table->json('commands');
            $table->string('command_trigger')->nullable();
            $table->unsignedMediumInteger('command_timer')->nullable();
            $table->boolean('is_indefinite')->default(0);
            $table->boolean('is_public')->default(0);
            $table->boolean('is_active')->default(0);
            $table->unsignedMediumInteger('votes')->default(0);
            $table->unsignedMediumInteger('copy_count')->default(0);
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
