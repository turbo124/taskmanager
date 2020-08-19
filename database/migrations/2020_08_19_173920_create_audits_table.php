<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audits', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('data', 65535);
			$table->timestamps();
			$table->string('entity_class', 100);
			$table->integer('entity_id')->unsigned();
			$table->integer('notification_id')->unsigned()->index('notification_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('audits');
	}

}
