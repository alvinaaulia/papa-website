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
        Schema::table('master_salary', function (Blueprint $table) {
            $table->string('tier_grade', 50)->nullable()->after('net_salary');
            $table->decimal('evaluation_score', 5, 2)->nullable()->after('tier_grade');
            $table->date('period_start')->nullable()->after('evaluation_score');
            $table->date('period_end')->nullable()->after('period_start');
            $table->text('assessment_notes')->nullable()->after('period_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_salary', function (Blueprint $table) {
            $table->dropColumn([
                'tier_grade',
                'evaluation_score',
                'period_start',
                'period_end',
                'assessment_notes',
            ]);
        });
    }
};
