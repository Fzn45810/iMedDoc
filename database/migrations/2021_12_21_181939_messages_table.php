<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
            ->references('id')->on('doctor')
            ->onDelete('cascade');

            $table->foreignId('patient_id')
            ->references('id')->on('patient')
            ->onDelete('cascade');

            $table->longText('message');
            $table->integer('sender_receiver');
            $table->boolean('isread');
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
        Schema::dropIfExists('messages');
    }
}
