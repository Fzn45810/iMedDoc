<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Invoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();

            $table->string('bill_to')->nullable();
            // date type should be date. formate 2021-12-14
            $table->date('date')->nullable();

            $table->foreignId('income_category_id')
            ->references('id')->on('income_category')
            ->onDelete('cascade');

            $table->foreignId('insurance_company_id')
            ->nullable()
            ->references('id')->on('insurance_company')
            ->onDelete('cascade');

            $table->string('insurance_number')->nullable();

            $table->foreignId('patient_id')
            ->references('id')->on('patient')
            ->onDelete('cascade');

            // this is contact id
            $table->foreignId('solicitor_id')
            ->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');

            $table->string('sub_total')->nullable();
            $table->string('tax')->nullable();
            $table->string('tax_percentage')->nullable();
            $table->string('net_total')->nullable();

            $table->string('paid')->nullable();

            $table->string('memo')->nullable();

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
        Schema::dropIfExists('invoice');
    }
}
