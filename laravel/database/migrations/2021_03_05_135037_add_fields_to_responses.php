<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToResponses extends Migration
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
            $table->integer('rating')->default(false)->nullable();
            $table->boolean('confirmed')->default(false)->nullable();
            $table->boolean('deleted')->default(false)->nullable();
            $table->integer('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign(['user_id']);
            $table->dropColumn(['rating', 'confirmed', 'deleted', ' user_id']);
        });
    }
}
