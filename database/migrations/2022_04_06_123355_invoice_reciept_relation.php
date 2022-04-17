<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceRecieptRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_receipt_relat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
            ->references('id')->on('invoice')
            ->onDelete('cascade');

            $table->foreignId('receipt_id')
            ->references('id')->on('receipt')
            ->onDelete('cascade');

            $table->string('relat_r_tax');
            $table->string('relat_waived');
            $table->string('relat_payment');

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
        Schema::dropIfExists('invoice_receipt_relat');
    }
}
