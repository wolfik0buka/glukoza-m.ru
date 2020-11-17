<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 255)->nullable()->default(NULL);
            $table->string('email', 255)->nullable()->default(NULL);
            $table->string('phone', 255)->nullable()->default(NULL);
            $table->string('content', 1024)->nullable()->default(NULL);
            $table->dateTime('created_at')->nullable()->default(NULL);
            $table->dateTime('updated_at')->nullable()->default(NULL);
            $table->dateTime('deleted_at')->nullable()->default(NULL);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('messages');
	}

}
