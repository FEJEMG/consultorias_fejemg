<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('email', 100)->unique();
			$table->string('password', 60);
			$table->string('phone', 20);
			//Administrator?
			$table->boolean('administrator')->default(false);
			//Activated?
			$table->boolean('status')->default(false);
			$table->rememberToken();
			$table->timestamps('create_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
