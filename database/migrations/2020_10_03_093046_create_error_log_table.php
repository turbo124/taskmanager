<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('error_log', function(Blueprint $table)
		{
			$table->integer(''id'', true);
			$table->text('data')->nullable();
			$table->integer('customer_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('account_id')->unsigned();
			$table->string(''entity'', 100);
			$table->integer('entity_id');
			$table->string(''error_type'', 100);
			$table->string(''error_result'', 100);
			$table->timestamps(10);
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
		Schema::drop('error_log');
	}

}
