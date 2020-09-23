<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaseTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('case_templates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('description', 65535);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('send_on');
			$table->integer('account_id')->unsigned();
			$table->integer('user_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('case_templates');
	}

}
