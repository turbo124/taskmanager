<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->dateTime('created_at')->nullable();
			$table->softDeletes();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->index('user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_categories');
	}

}
