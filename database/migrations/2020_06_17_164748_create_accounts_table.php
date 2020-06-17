<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('ip')->nullable();
			$table->string('subdomain')->nullable();
			$table->string('first_day_of_week')->nullable();
			$table->string('first_month_of_year')->nullable();
			$table->string('portal_domain')->nullable();
			$table->smallInteger('enable_modules')->default(0);
			$table->text('custom_fields', 65535);
			$table->text('settings', 65535)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('domain_id')->unsigned()->default(1)->index();
			$table->string('slack_webhook_url')->nullable();
			$table->integer('transaction_fee')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}

}
