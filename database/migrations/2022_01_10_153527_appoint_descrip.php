<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppointDescrip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appoint_descrip', function (Blueprint $table) {
            $table->id();

            $table->longText('appoint_description');

            $table->foreignId('procedures_id')
            ->references('id')->on('procedures')
            ->onDelete('cascade');

            $table->string('color_code')->nullable();
            $table->string('appointments')->nullable();

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
        Schema::dropIfExists('appoint_descrip');
    }
}
