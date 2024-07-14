<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->foreignId('commission_request_id');

            $table->boolean('tax_basis');
            $table->string('tax_basis_reference_remarks')->nullable();
            $table->decimal('tax_basis_reference_amount',10,2)->nullable();
            $table->decimal('tcp',10,2);
            $table->decimal('requested_rate',5,2);

            $table->decimal('gross_commission',10,2);
            $table->decimal('percentage_released',5,2);
            $table->decimal('sub_total',10,2);
            $table->decimal('wht_percent',10,2);
            $table->decimal('wht_amount',10,2);
            $table->decimal('vat_percent',5,2);
            $table->decimal('vat_amount',10,2);
            $table->decimal('net_commission_less_vat',10,2);
            $table->decimal('total_receivable_without_deduction',10,2);
            $table->decimal('net_commission_less_deductions',10,2);
            $table->enum('status',['pending','approved']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_vouchers');
    }
};
