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
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id('id_salary_grade');
            $table->string('grade_code')->unique();
            $table->string('grade_name');
            $table->unsignedTinyInteger('min_score');
            $table->unsignedTinyInteger('max_score');
            $table->decimal('base_salary', 15, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
