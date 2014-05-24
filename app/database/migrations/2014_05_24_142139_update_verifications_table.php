<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVerificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::tabe('verifications', function(Blueprint $table)
		{
			$table->dropColumn('email');


			$table->foreign('user_id')->references('id')->on('users');



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
		//
	}

}
