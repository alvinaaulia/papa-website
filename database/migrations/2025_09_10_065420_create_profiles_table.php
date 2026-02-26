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
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id_profile')->primary();
            $table->foreignUuid('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('id_last_education')->nullable()->references('id_last_education')->on('last_educations')->onDelete('set null');
            $table->foreignUuid('id_experience')->nullable()->references('id_experience')->on('experiences')->onDelete('set null');
            $table->string('nip')->nullable();
            $table->string('name');
            $table->enum('type_of_employment', ['online', 'offline', 'freelance'])->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('address')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('province')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('nik')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
