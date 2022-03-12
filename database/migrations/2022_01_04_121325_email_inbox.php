<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailInbox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_inbox', function (Blueprint $table) {
            $table->id();

            $table->foreignId('email_id')
            ->references('id')->on('all_email')
            ->onDelete('cascade');

            $table->boolean('isread');
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
        Schema::dropIfExists('email_inbox');
    }
}
