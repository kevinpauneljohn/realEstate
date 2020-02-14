<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('middlename')->nullable()->change();
            $table->string('mobileNo')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('date_of_birth')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('middlename')->change();
            $table->string('mobileNo')->change();
            $table->text('address')->change();
            $table->string('date_of_birth')->change();
            $table->string('email')->change();
        });
    }
}
