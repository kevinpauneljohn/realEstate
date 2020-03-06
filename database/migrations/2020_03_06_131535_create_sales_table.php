<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->uuid('lead_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('model_unit_id')->nullable();
            $table->string('total_contract_price');
            $table->string('discount')->nullable();
            $table->string('reservation_fee')->nullable();
            $table->string('equity')->nullable();
            $table->string('loanable_amount')->nullable();
            $table->string('financing')->nullable();
            $table->string('terms')->nullable();
            $table->text('details')->nullable();
            $table->string('commission_rate')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('model_unit_id')->references('id')->on('model_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
