<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dictation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->string('file_name');

            $table->string('dictation_date');
            $table->string('dictation_time');
            $table->string('duration');
            $table->string('status')->default('Waiting for send');

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
        Schema::dropIfExists('dictation');
    }
}
