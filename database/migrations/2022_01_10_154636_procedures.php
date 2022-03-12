<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Procedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();

            $table->longText('procedure_name');
            $table->string('code')->nullable();
            $table->string('rate')->nullable();
            // This Should be Chage to Template ID
            $table->string('template')->nullable();
            $table->string('color_code')->nullable();
            $table->integer('duration_h')->nullable();
            $table->integer('duration_m')->nullable();

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
        Schema::dropIfExists('procedures');
    }
}
