<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Servers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cloud_servers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cloud_instance_id')->nullable();
            $table->string('name')->nullable();
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->string('provider');
            $table->string('ip_address');
            $table->integer('location');
            $table->integer('ram');
            $table->integer('available_ram');
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        Schema::create('minecraft_servers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cloud_server_id');
            $table->integer('ram');
            $table->integer('user_id');
            $table->integer('port');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('cloud_servers');
        Schema::dropIfExists('minecraft_servers');
    }
}
