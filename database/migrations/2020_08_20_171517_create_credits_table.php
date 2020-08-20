<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index('credits_user_id_foreign');
			$table->integer('assigned_to')->unsigned()->nullable();
			$table->integer('account_id')->unsigned()->index();
			$table->integer('status_id')->unsigned();
			$table->string('number')->nullable();
			$table->date('date')->nullable();
			$table->boolean('is_deleted')->default(0);
			$table->decimal('total', 16, 4);
			$table->decimal('balance', 16, 4);
			$table->dateTime('last_viewed')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('invoice_id')->unsigned()->nullable();
			$table->text('footer', 65535)->nullable();
			$table->text('public_notes', 65535)->nullable();
			$table->text('terms', 65535)->nullable();
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->string('custom_value3')->nullable();
			$table->string('custom_value4')->nullable();
			$table->text('line_items', 65535)->nullable();
			$table->date('due_date')->nullable();
			$table->dateTime('partial_due_date')->nullable();
			$table->boolean('is_amount_discount')->default(0);
			$table->string('po_number')->nullable();
			$table->decimal('discount_total', 16, 4)->default(0.0000);
			$table->decimal('tax_total', 16, 4)->default(0.0000);
			$table->text('private_notes', 65535)->nullable();
			$table->string('tax_rate_name')->nullable();
			$table->decimal('tax_rate', 13, 3)->default(0.000);
			$table->integer('design_id')->unsigned()->nullable();
			$table->decimal('transaction_fee', 16, 4)->nullable();
			$table->decimal('shipping_cost', 16, 4)->nullable();
			$table->boolean('transaction_fee_tax')->default(0);
			$table->boolean('shipping_cost_tax')->default(0);
			$table->decimal('sub_total', 16, 4);
			$table->decimal('partial', 16, 4);
			$table->decimal('gateway_fee', 16, 4)->default(0.0000);
			$table->boolean('gateway_percentage')->default(0);
			$table->dateTime('date_reminder_last_sent')->nullable();
			$table->integer('currency_id')->nullable();
			$table->decimal('exchange_rate', 12)->default(0.00);
			$table->unique(['account_id','number']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('credits');
	}

}
