<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable()->default(NULL);
            $table->date('created_at');
            $table->date('updated_at');
            $table->date('deleted_at');
        });

        Schema::create('property_link_cat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_cat')->unsigned();
            $table->integer('id_property')->unsigned();
        });

        Schema::create('property_link_value', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value', 255)->nullable()->default(NULL);
            $table->integer('id_property')->unsigned();
        });

        Schema::create('property_link_tovar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tovar')->unsigned();
            $table->integer('id_value')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_link_tovar');
        Schema::dropIfExists('property_link_value');
        Schema::dropIfExists('property_link_cat');
        Schema::dropIfExists('property');
    }
}
