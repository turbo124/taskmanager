<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->string('accepted_credit_cards', 100);
			$table->boolean('require_cvv')->default(1);
			$table->boolean('show_billing_address')->nullable()->default(1);
			$table->boolean('show_shipping_address')->nullable()->default(1);
			$table->boolean('update_details')->nullable()->default(0);
			$table->text('config', 65535);
			$table->text('fees_and_limits', 65535);
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('exclude_from_checkout')->default(0);
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
