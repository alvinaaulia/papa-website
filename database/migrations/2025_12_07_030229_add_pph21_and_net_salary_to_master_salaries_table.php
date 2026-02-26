<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('master_salary', function (Blueprint $table) {
            $table->decimal('pph21', 15, 2)->default(0)->after('salary_amount');
            $table->decimal('net_salary', 15, 2)->default(0)->after('pph21');
        });
    }

    public function down()
    {
        Schema::table('master_salary', function (Blueprint $table) {
            $table->dropColumn(['pph21', 'net_salary']);
        });
    }
};
