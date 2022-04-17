<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Receipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt', function (Blueprint $table) {
            $table->id();

            // date type should be date. formate 2021-12-14
            $table->date('date')->nullable();
            $table->string('received_from');
            $table->string('mode_of_payment');
            $table->string('cheque_date')->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('bank_name')->nullable();
            
            // if received_from type is patient
            $table->foreignId('patient_id')
            ->nullable()
            ->references('id')->on('patient')
            ->onDelete('cascade');

            // this is contact id
            // if received_from type is Third Party
            $table->foreignId('third_party')
            ->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');


            // if received_from type is Insurance Company
            $table->foreignId('rec_insur_comp_id')
            ->nullable()
            ->references('id')->on('insurance_company')
            ->onDelete('cascade');

            $table->string('r_tax');
            $table->string('waived');
            $table->string('payment');
            $table->string('receipt_memo');

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
        Schema::dropIfExists('receipt');
    }
}
