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
		Schema::create('Users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
	        $table->string('email')->unique();
	        $table->string('password');
	        $table->string('first_name')->default('');
	        $table->string('last_name')->default('');
	        $table->smallInteger('role')->default(0);
	        $table->boolean('verified')->default(FALSE);
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
