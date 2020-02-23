<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->uuid('lead_id');
            $table->text('details')->nullable();
            $table->string('schedule');
            $table->string('parent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('lead_activities');
    }
}
