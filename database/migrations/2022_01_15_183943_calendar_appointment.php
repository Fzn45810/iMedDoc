<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalendarAppointment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_appointment', function (Blueprint $table) {
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

            $table->foreignId('description_id')
            ->references('id')->on('appoint_descrip')
            ->onDelete('cascade');

            $table->foreignId('location_id')
            ->references('id')->on('clinic_location')
            ->onDelete('cascade');

            $table->foreignId('doctor_id')
            ->references('id')->on('doctor')
            ->onDelete('cascade');

            $table->time('appoint_time');
            $table->string('clinic_physio')->nullable();
            $table->date('appoint_date');
            $table->string('appoint_month');
            $table->string('appoint_year');
            $table->string('appoint_notes')->nullable();
            $table->string('appoint_temp')->nullable();

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
        Schema::dropIfExists('calendar_appointment');
    }
}
