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
        Schema::create('master_leave', function (Blueprint $table) {
            $table->id('ID_master_leave');
            $table->string('name');
            $table->integer('deduction');
            $table->integer('leave_stock')->default(0);
            $table->enum('status',['process','approved', 'revision', 'suspended'])->default('process');
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
