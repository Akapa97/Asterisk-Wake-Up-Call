<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCdrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cdrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('clid', 80)->default('');
            $table->string('src', 80)->default('');
            $table->string('dst', 80)->default('');
            $table->string('dcontext', 80)->default('');
            $table->string('lastapp', 200)->default('');
            $table->string('lastdata', 200)->default('');
            $table->float('duration', 8, 3)->nullable();
            $table->float('billsec', 8, 3)->nullable();
            $table->enum('answered', ['ANSWERED', 'BUSY', 'FAILED', 'NO ANSWER', 'CONGESTION'])->nullable();
            $table->string('channel', 50)->nullable();
            $table->string('dstchannel', 50)->nullable();
            $table->string('amaflags', 50)->nullable();
            $table->string('accountcode', 20)->nullable();
            $table->string('uniqueid', 32)->default('');
            $table->float('userfield', 8, 3)->nullable();
            $table->string('disposition', 45);
            $table->string('linkedid', 150);
            $table->integer('sequence');
            $table->index('dst');
            $table->index('src');
            $table->index('dcontext');
            $table->index('clid');
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
        Schema::dropIfExists('cdrs');
    }
}
