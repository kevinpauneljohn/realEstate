<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToClientProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('builder_id')->nullable()->after('architect_id');

            $table->foreign('builder_id')->references('id')->on('builders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_projects', function (Blueprint $table) {
            $table->dropForeign('client_projects_builder_id_foreign');
            $table->dropColumn('builder_id');
        });
    }
}
