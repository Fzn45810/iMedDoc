<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WaitingList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waiting_list', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
            ->nullable()
            ->references('id')->on('patient')
            ->onDelete('cascade');

            $table->date('waitingFrom');//formate 2021-12-14

            // appoint_type
            $table->foreignId('waitingFor')
            ->references('id')->on('appoint_type')
            ->onDelete('cascade');

            $table->foreignId('procedures_id')
            ->nullable()
            ->references('id')->on('procedures')
            ->onDelete('cascade');

            $table->foreignId('appoint_id')
            ->nullable()
            ->references('id')->on('appoint_descrip')
            ->onDelete('cascade');

            $table->string('priority');
            $table->string('notes')->nullable();

            $table->string('status')->default('Waiting');

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
        Schema::dropIfExists('waiting_list');
    }
}
