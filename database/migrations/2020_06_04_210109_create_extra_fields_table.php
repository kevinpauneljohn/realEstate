<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount_withdrawal_request_id');
            $table->decimal('amount',8,2);
            $table->string('extra_field_description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('amount_withdrawal_request_id')->references('id')->on('amount_withdrawal_requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_fields');
    }
}
