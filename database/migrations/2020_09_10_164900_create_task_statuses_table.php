<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_statuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->string('icon');
			$table->timestamps();
			$table->integer('is_active')->default(1);
			$table->integer('task_type')->default(1);
			$table->string('column_color');
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->nullable()->index('user_id');
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_statuses');
	}

}
