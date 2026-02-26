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
        Schema::create('leave', function (Blueprint $table) {
            $table->id('id_leave');
            $table->foreignUuid('id_user')->references('id')->on('users');
            $table->string('leave_type');
            $table->string('reason')->nullable();
            $table->date('start_of_leave');
            $table->date('end_of_leave');
            $table->integer('amount_of_leave');
            $table->text('notes')->nullable();
            $table->string('leave_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('status_pm', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_hrd', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_director', ['pending', 'approved', 'rejected'])->default('pending');
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
