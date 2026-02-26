<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id('id_position');
            $table->unsignedBigInteger('id_master_position');
            $table->foreignUuid('id_user')->references('id')->on('users');
            $table->date('entry_date');
            $table->timestamps();

            $table->foreign('id_master_position')
                  ->references('id_master_position')
                  ->on('master_positions')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
