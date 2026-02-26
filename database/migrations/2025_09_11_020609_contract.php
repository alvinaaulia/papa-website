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
        Schema::create('contract', function (Blueprint $table) {
            $table->id('id_contract');
            $table->foreignUuid('id_user')->references('id')->on('users');
            $table->string('contract_number');
            $table->date('contract_date');
            $table->date('start_contract');
            $table->date('end_contract');
            $table->integer('leave_stock')->default(0);
            $table->enum('status', ['process', 'approved', 'revision', 'suspended'])->default('process');
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
