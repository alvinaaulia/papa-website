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
        Schema::table('salary', function (Blueprint $table) {
            $table->decimal('gross_salary', 15, 2)->nullable()->after('salary_amount');
            $table->decimal('total_deductions', 15, 2)->nullable()->after('gross_salary');
            $table->json('calculation_facts')->nullable()->after('total_deductions');
            $table->json('rule_engine_result')->nullable()->after('calculation_facts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary', function (Blueprint $table) {
            $table->dropColumn([
                'gross_salary',
                'total_deductions',
                'calculation_facts',
                'rule_engine_result',
            ]);
        });
    }
};

