<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->string('notifiable_type');
			$table->bigInteger('notifiable_id')->unsigned();
			$table->text('data');
			$table->dateTime('read_at')->nullable();
			$table->timestamps(10);
			$table->integer('account_id')->unsigned()->nullable();
			$table->integer('entity_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
