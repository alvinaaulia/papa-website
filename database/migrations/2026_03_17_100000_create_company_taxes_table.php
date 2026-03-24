<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_taxes', function (Blueprint $table) {
            $table->bigIncrements('tax_id');
            $table->string('tax_code', 50)->unique();
            $table->string('tax_name', 150);
            $table->enum('tax_type', ['PPH21', 'BPJS', 'OTHER'])->default('PPH21');
            $table->enum('calculation_method', ['FIXED', 'PERCENTAGE', 'PROGRESSIVE', 'GROSS_UP'])->default('PERCENTAGE');
            $table->decimal('tax_rate', 8, 4)->nullable();
            $table->decimal('income_min', 15, 2)->nullable();
            $table->decimal('income_max', 15, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_taxes');
    }
};
