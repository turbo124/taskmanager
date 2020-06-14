<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('timers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('user_id')->unsigned()->index('timers_user_id_foreign');
			$table->dateTime('started_at')->nullable();
			$table->dateTime('stopped_at')->nullable();
			$table->timestamps();
			$table->integer('task_id')->unsigned()->index('task_id');
			$table->integer('account_id')->unsigned()->index('account_id');
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
		Schema::drop('timers');
	}

}
