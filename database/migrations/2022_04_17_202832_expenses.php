<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Expenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->string('expenses_date');
            $table->string('expenses_amount');

            $table->string('expenses_category');
            $table->string('expenses_payment_mode');

            // if payment mode is cheque
            $table->string('expens_bank_name')->nullable();
            $table->string('expens_cheque_no')->nullable();
            $table->string('expens_cheque_date')->nullable();

            // if payment mode is credit card
            $table->string('expens_card_type')->nullable();
            $table->string('expens_card_name')->nullable();
            $table->string('expens_card_no')->nullable();
            $table->string('expens_card_expi_date')->nullable();

            // if payment mode is direct debit
            $table->string('expens_refrence_no')->nullable();
            $table->string('expens_ref_bank_name')->nullable();

            $table->string('expens_details')->nullable();

            $table->string('expens_file_one')->nullable();
            $table->string('expens_file_two')->nullable();
            $table->string('expens_file_three')->nullable();

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
