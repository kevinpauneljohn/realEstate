<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTouchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_touches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('lead_id');
            $table->string('medium');
            $table->date('date');
            $table->string('time',0);
            $table->string('resolution');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_touches');
    }
}
