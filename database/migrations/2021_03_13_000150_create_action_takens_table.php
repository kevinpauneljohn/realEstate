<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionTakensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_takens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_checklist_id');
            $table->text('action');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('task_checklist_id')->references('id')->on('task_checklists');
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
        Schema::dropIfExists('action_takens');
    }
}
