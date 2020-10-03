<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerGatewaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_gateways', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('client_gateway_tokens_account_id_foreign');
			$table->integer('customer_id')->unsigned()->nullable()->index('client_gateway_tokens_customer_id_foreign');
			$table->text('token')->nullable();
			$table->integer('company_gateway_id')->unsigned()->default(1);
			$table->string('gateway_customer_reference')->nullable();
			$table->integer('gateway_type_id')->unsigned()->default(1);
			$table->boolean('is_default')->default(0);
			$table->text('meta')->nullable();
			$table->softDeletes();
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
		Schema::drop('customer_gateways');
	}

}
