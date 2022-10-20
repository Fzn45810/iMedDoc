<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SurgeryProceRela extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surgery_proced_relat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('surgery_id')
            ->references('id')->on('calendar_surgery')
            ->nullable()
            ->onDelete('cascade');

            $table->foreignId('procedures_id')
            ->references('id')->on('procedures')
            ->nullable()
            ->onDelete('cascade');

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
        Schema::dropIfExists('surgery_proced_relat');
    }
}
