<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('master_positions', function (Blueprint $table) {
            $table->id('id_master_position');
            $table->string('position_name', 100);
            $table->enum('status', ['onsite', 'online', 'hybrid'])->default('onsite');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_positions');
    }
}
