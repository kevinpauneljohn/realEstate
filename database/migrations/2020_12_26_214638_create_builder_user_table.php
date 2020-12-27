<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuilderUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('builder_user', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->unsignedBigInteger('builder_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('builder_id')->references('id')->on('builders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('builder_user');
    }
}
