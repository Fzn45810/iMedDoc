<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsuProceRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_proced_relat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('insurance_id')
            ->nullable()
            ->references('id')->on('insurance_company')
            ->onDelete('cascade');

            $table->foreignId('procedures_id')
            ->nullable()
            ->references('id')->on('procedures')
            ->onDelete('cascade');

            $table->string('rates')->nullable();

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
        Schema::dropIfExists('insurance_proced_relat');
    }
}
