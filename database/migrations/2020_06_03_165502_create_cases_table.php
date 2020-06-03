<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cases', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('subject');
			$table->text('message', 65535);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('customer_id')->unsigned()->index('customer_id');
			$table->integer('status_id');
			$table->boolean('is_deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cases');
	}

}
