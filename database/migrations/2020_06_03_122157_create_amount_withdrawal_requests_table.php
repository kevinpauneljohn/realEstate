<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountWithdrawalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_request_id');
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('original_amount',8,2);
            $table->decimal('requested_amount',8,2);
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cash_request_id')->references('id')->on('cash_requests');
            $table->foreign('wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amount_withdrawal_requests');
    }
}
