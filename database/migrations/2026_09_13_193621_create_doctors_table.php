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
        Schema::create('Doctors', function (Blueprint $table) {
            $table->increments('DoctorID');
            $table->string('FirstName', 50);
            $table->string('LastName', 50);
            $table->string('Specialization', 100);
            $table->string('ContactNumber', 20)->nullable();
            $table->string('Email', 100)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Doctors');
    }
};