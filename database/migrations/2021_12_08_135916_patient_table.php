<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreignId('title_type_id')
            ->references('id')->on('title_table')
            ->onDelete('cascade');

            $table->string('surname');
            $table->string('dname')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();

            $table->foreignId('gp')->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');

            $table->foreignId('solicitor')->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');

            $table->foreignId('referingDr')->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');

            $table->foreignId('pharmacy')->nullable()
            ->references('id')->on('contacts')
            ->onDelete('cascade');

            $table->string('dateOfAccident')->nullable();
            $table->string('referralDate')->nullable();
            $table->string('timeOfAccident')->nullable();
            $table->string('primaryDiagnosisType')->nullable();
            $table->string('side')->nullable();
            $table->string('dateOfBirth')->nullable();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('homePhone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('occupation')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->string('religion')->nullable();

            $table->foreignId('patientType')
            ->nullable()
            ->references('id')->on('patient_type')
            ->onDelete('cascade');

            $table->string('caseRefNo')->nullable();
            $table->string('notes')->nullable();
            $table->string('primaryDiagnosis')->nullable();

            // for updating profile
            $table->foreignId('insurance_comp_id')
            ->nullable()
            ->references('id')->on('insurance_company')
            ->onDelete('cascade');

            $table->foreignId('insurance_plane_id')
            ->nullable()
            ->references('id')->on('insurance_plane')
            ->onDelete('cascade');

            $table->string('insurance_number')->nullable();

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
        Schema::dropIfExists('patient');
    }
}
