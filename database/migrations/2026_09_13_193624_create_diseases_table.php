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
        Schema::create('Diseases', function (Blueprint $table) {
            $table->increments('DiseaseID');
            $table->unsignedInteger('ConsultationID');
            $table->string('NameDisease', 150);
            $table->date('Date');

            $table->foreign('ConsultationID')->references('ConsultationID')->on('Consultations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Diseases');
    }
};