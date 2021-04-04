<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaycationAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staycation_appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('staycation_client_id');
            $table->dateTime('check_in',0);
            $table->dateTime('check_out',0);
            $table->double('amount', 8, 2);
            $table->text('pax');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('staycation_client_id')->references('id')->on('staycation_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staycation_appointments');
    }
}
