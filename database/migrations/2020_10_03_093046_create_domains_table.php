<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('domains', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_id')->unsigned()->nullable()->index();
			$table->integer('default_account_id')->unsigned();
			$table->timestamps(10);
			$table->softDeletes();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('customer_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('domains');
	}

}
