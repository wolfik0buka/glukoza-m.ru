<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTovarSumbolCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tovar', function (Blueprint $table) {
            //
            $table->string('symbol_code')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tovar', function (Blueprint $table) {
            //
             $table->dropUnique('symbol_code');
        });
    }
}
