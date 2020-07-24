<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscriptions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 100);
			$table->string('target_url', 100);
			$table->integer('event_id')->unsigned();
			$table->softDeletes();
			$table->timestamps();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->string('format')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscriptions');
	}

}
