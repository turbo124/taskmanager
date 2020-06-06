<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('company_ledgers_account_id_foreign');
			$table->integer('customer_id')->unsigned()->nullable()->index('company_ledgers_customer_id_foreign');
			$table->integer('user_id')->unsigned()->nullable();
			$table->decimal('amount', 16, 4)->nullable();
			$table->decimal('updated_balance', 16, 4)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->text('hash', 65535)->nullable();
			$table->integer('transactionable_id')->unsigned();
			$table->string('transactionable_type');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions');
	}

}
