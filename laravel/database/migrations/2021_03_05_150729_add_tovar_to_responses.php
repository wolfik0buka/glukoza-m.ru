<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTovarToResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('responses', function (Blueprint $table) {
            $table->integer('tovar_id')->nullable();
            $table->foreign('tovar_id')->references('id')->on('tovar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('responses', function (Blueprint $table) {
            $table->dropForeign(['tovar_id']);
            $table->dropColumn(['tovar_id']);
        });
    }
}
