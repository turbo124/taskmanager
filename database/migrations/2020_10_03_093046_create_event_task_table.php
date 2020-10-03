<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_task', function(Blueprint $table)
		{
			$table->bigInteger(''id'', true)->unsigned();
			$table->integer('task_id')->unsigned()->index('task_id');
			$table->integer('event_id')->unsigned()->index('event_id');
			$table->timestamps(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('event_task');
	}

}
