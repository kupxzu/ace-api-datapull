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
        Schema::create('Patients', function (Blueprint $table) {
            $table->increments('PatientID');
            $table->string('FirstName', 50);
            $table->string('LastName', 50);
            $table->string('Gender', 10)->nullable();
            $table->date('BirthDate')->nullable();
            $table->string('ContactNumber', 20)->nullable();
            $table->string('Address', 200)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Patients');
    }
};