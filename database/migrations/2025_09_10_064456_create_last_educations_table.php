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
        Schema::create('last_educations', function (Blueprint $table) {
            $table->uuid('ID_last_education')->primary();
            $table->string('major')->nullable();
            $table->string('instance')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('sertificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('last_educations');
    }
};
