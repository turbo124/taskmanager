<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyGatewaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_gateways', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('company_gateways_account_id_foreign');
			$table->integer('user_id')->unsigned()->index('company_gateways_user_id_foreign');
			$table->string('gateway_key')->index('company_gateways_gateway_key_foreign');
			$table->string(''accepted_credit_cards'', 100);
			$table->boolean('require_cvv')->default(1);
			$table->boolean('show_billing_address')->nullable()->default(1);
			$table->boolean('show_shipping_address')->nullable()->default(1);
			$table->boolean('update_details')->nullable()->default(0);
			$table->text('config');
			$table->text('fees_and_limits');
			$table->timestamps(10);
			$table->softDeletes();
			$table->boolean('exclude_from_checkout')->default(0);
			$table->boolean('token_billing_enabled')->default(0);
			$table->string('name');
			$table->boolean('should_store_card')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_gateways');
	}

}
