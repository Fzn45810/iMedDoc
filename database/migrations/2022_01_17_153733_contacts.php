<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contact_type_id')
            ->references('id')->on('contact_type')
            ->onDelete('cascade');

            $table->foreignId('title_type_id')
            ->references('id')->on('title_table')
            ->onDelete('cascade');

            $table->string('surname')->nullable();
            $table->string('fname')->nullable();
            $table->string('dname')->nullable();
            $table->string('entityname')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();
            $table->string('workphone')->nullable();
            $table->string('homephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('fax')->nullable();

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
        Schema::dropIfExists('contacts');
    }
}
