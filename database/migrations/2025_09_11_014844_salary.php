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
        Schema::create('salary', function (Blueprint $table) {
            $table->id('ID_salary');
            $table->unsignedBigInteger('ID_master_salary');

            $table->foreign('ID_master_salary')
                ->references('ID_master_salary')
                ->on('master_salary')
                ->onDelete('cascade');

            $table->decimal('salary_amount',15,2);
            $table->date('salary_date');
            $table->string('transfer_proof')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
