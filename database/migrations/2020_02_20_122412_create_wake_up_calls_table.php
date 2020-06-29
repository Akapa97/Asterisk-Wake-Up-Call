<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWakeUpCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wake_up_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('datetime');
            $table->integer('ext');
            $table->integer('tries');
            $table->integer('waittime');
            $table->integer('retrytime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wake_up_calls');
    }
}
