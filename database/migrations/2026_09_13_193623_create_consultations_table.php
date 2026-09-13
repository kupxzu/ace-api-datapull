<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Consultations', function (Blueprint $table) {
            $table->increments('ConsultationID');
            $table->unsignedInteger('PatientID');
            $table->unsignedInteger('DoctorID');
            $table->dateTime('ConsultationDate');
            $table->string('Notes', 500)->nullable();

            $table->foreign('PatientID')->references('PatientID')->on('Patients');
            $table->foreign('DoctorID')->references('DoctorID')->on('Doctors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Consultations');
    }
};