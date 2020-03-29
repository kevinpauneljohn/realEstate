<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlkLotToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('lot_area')->nullable()->after('model_unit_id');
            $table->string('floor_area')->nullable()->after('lot_area');
            $table->string('phase')->nullable()->after('floor_area');
            $table->string('block')->nullable()->after('phase');
            $table->string('lot')->nullable()->after('block');
            $table->string('processing_fee')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['lot_area','floor_area','phase','block','lot','processing_fee']);
        });
    }
}
