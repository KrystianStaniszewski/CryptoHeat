<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Machine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine', function (Blueprint $table) {
            $table->id();
            $table->string('connexionKey', 150)->nullable();
            $table->string('name', 45)->nullable();
            $table->string('URL')->nullable();
            $table->string('temperature')->nullable();
            $table->bigInteger('User_id')->unsigned()->nullable();
        });

        Schema::table('machine', function (Blueprint $table) {
            $table->foreign('User_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine');
    }
}
