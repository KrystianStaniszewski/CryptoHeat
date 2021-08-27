<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverclokingParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Overcloking_params', function (Blueprint $table) {
            $table->id();
            $table->integer('Core_clock')->nullable();
            $table->integer('Memory_clock')->nullable();
            $table->integer('Fan')->nullable();
            $table->integer('Pw_limit')->nullable();
            $table->integer('Delay')->nullable();
            $table->integer('CoreVoltage')->nullable();
            $table->integer('MemoryController_voltage')->nullable();
            $table->integer('MemoryVoltage')->nullable();
            $table->bigInteger('Gpu_id')->unsigned()->nullable();
        });

        Schema::table('Overcloking_params', function (Blueprint $table) {
            $table->foreign('Gpu_id')->references('id')->on('gpu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_overcloking_params');
    }
}
