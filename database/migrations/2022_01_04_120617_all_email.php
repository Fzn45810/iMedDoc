<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_email', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('from');
            $table->string('to_name')->nullable();
            $table->string('from_name')->nullable();
            $table->string('subject');
            $table->longText('email_text');
            $table->string('attachment')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
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
        Schema::dropIfExists('all_email');
    }
}
