<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalendarTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appoint_type_id')
            ->references('id')->on('appoint_type')
            ->onDelete('cascade');

            // $table->foreignId('calendar_id')
            // ->references('id')->on('calendar')
            // ->onDelete('cascade');

            $table->time('task_time');
            $table->date('task_date');

            $table->foreignId('doctor_id')
            ->references('id')->on('doctor')
            ->onDelete('cascade');

            $table->string('remind')->nullable();
            $table->string('remind_to')->nullable();
            $table->string('color')->nullable();
            $table->longText('task_text');
            $table->string('status')->default('NC');

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
        Schema::dropIfExists('calendar_tasks');
    }
}
