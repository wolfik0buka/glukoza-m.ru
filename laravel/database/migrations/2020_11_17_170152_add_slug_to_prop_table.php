<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToPropTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->default(NULL);
        });
        Schema::table('property_link_value', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('property_link_value', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
