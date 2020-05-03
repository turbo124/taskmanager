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
			$table->timestamp('started_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('stopped_at')->nullable();
			$table->timestamps();
			$table->integer('task_id')->unsigned()->index('task_id');
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
