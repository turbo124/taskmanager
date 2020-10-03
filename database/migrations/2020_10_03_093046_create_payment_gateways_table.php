<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_gateways', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('key')->unique('gateways_key_unique');
			$table->string('provider');
			$table->integer('default_gateway_type_id')->unsigned()->default(1);
			$table->timestamps(10);
			$table->boolean('offsite_only')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_gateways');
	}

}
