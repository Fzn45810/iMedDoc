<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LodgementReceiptRel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lodgement_receipt_rel', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lodgement_id')
            ->references('id')->on('lodgement')
            ->onDelete('cascade');

            $table->foreignId('receipt_id')
            ->references('id')->on('receipt')
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
        Schema::dropIfExists('lodgement_receipt_rel');
    }
}
