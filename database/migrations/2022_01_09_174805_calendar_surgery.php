<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalendarSurgery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_surgery', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appoint_type_id')
            ->references('id')->on('appoint_type')
            ->onDelete('cascade');

            // $table->foreignId('calendar_id')
            // ->references('id')->on('calendar')
            // ->onDelete('cascade');

            $table->foreignId('patient_id')
            ->references('id')->on('patient')
            ->onDelete('cascade');

            $table->foreignId('hospital_id')
            ->references('id')->on('hospital')
            ->onDelete('cascade');

            $table->time('surgery_from');

            $table->foreignId('doctor_id')
            ->references('id')->on('doctor')
            ->onDelete('cascade');

            $table->string('anesthetist')->nullable();
            $table->time('surgery_time');
            $table->date('surgery_date');
            $table->date('admission_date');
            $table->string('surgery_note')->nullable();
            $table->string('surgery_temp')->nullable();

            $table->string('status')->default('NA');

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
        Schema::dropIfExists('calendar_surgery');
    }
}
