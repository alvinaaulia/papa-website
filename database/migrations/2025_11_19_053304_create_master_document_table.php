<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new Class extends Migration
{
    public function up()
    {
        Schema::create('master_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_name');
            $table->enum('status', ['required', 'optional'])->default('required');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_documents');
    }
};