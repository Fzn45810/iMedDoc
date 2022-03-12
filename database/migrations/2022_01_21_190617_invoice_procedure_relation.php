<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceProcedureRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_proced_relat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
            ->references('id')->on('invoice')
            ->onDelete('cascade');

            $table->foreignId('procedures_id')
            ->references('id')->on('procedures')
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
        Schema::dropIfExists('invoice_proced_relat');
    }
}
