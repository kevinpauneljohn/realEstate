<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->string('type');
            $table->text('description');
            $table->longText('data');
            $table->string('table');
            $table->string('status');
            $table->uuid('approved_by')->nullable();
            $table->unsignedBigInteger('priority_id');
            $table->text('admin_report');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('priority_id')->references('id')->on('priorities');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thresholds');
    }
}
