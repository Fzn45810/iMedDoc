<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Lodgement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lodgement', function (Blueprint $table) {
            $table->id();

            $table->string('date');

            $table->foreignId('bank_id')
            ->references('id')->on('bank_details')
            ->onDelete('cascade');

            $table->string('total_amount');
            $table->string('lodgement_memo');

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
        Schema::dropIfExists('lodgement');
    }
}
