<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('overtime', function (Blueprint $table) {
            $table->uuid('id_overtime')->primary();
            $table->foreignUuid('id_user')->references('id')->on('users');
            $table->date('date');
            $table->time('start_overtime');
            $table->time('end_overtime');
            $table->integer('total_overtime');
            $table->text('description')->nullable();
            $table->string('proof')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
