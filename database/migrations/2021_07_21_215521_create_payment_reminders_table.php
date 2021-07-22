<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->boolean('completed');
            $table->date('schedule');
            $table->decimal('amount',8,2);
            $table->timestamps();

            $table->foreign('sales_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_reminders');
    }
}
