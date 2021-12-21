<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeartbeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heartbeats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_id');
            $table->unsignedTinyInteger('status');
            $table->unsignedSmallInteger('response_time');
            $table->json('data')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heartbeats');
    }
}
