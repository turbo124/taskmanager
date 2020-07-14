<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index('invoices_user_id_foreign');
			$table->integer('assigned_user_id')->unsigned()->nullable();
			$table->integer('status_id')->unsigned();
			$table->integer('recurring_id')->unsigned()->nullable();
			$table->string('number')->nullable();
			$table->boolean('is_amount_discount')->default(0);
			$table->integer('is_recurring')->nullable();
			$table->string('po_number')->nullable();
			$table->date('date')->nullable();
			$table->dateTime('due_date')->nullable();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->date('recurring_due_date')->nullable();
			$table->boolean('is_deleted')->default(0);
			$table->text('line_items', 65535)->nullable();
			$table->text('footer', 65535)->nullable();
			$table->text('public_notes', 65535)->nullable();
			$table->text('terms', 65535)->nullable();
			$table->decimal('total', 16, 4);
			$table->decimal('sub_total', 16, 4);
			$table->decimal('tax_total', 16, 4);
			$table->decimal('discount_total', 16, 4);
			$table->integer('parent_id')->nullable();
			$table->integer('frequency')->nullable();
			$table->decimal('balance', 16, 4);
			$table->decimal('partial', 16, 4)->nullable();
			$table->dateTime('partial_due_date')->nullable();
			$table->dateTime('last_viewed')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('task_id')->unsigned()->nullable();
			$table->integer('company_id')->unsigned()->nullable();
			$table->date('next_send_date')->nullable();
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->string('custom_value3')->nullable();
			$table->string('custom_value4')->nullable();
			$table->date('last_sent_date')->nullable();
			$table->text('private_notes', 65535)->nullable();
			$table->boolean('uses_inclusive_taxes')->default(0);
			$table->decimal('tax_rate', 13, 3)->nullable()->default(0.000);
			$table->string('tax_rate_name')->nullable();
			$table->integer('design_id')->unsigned()->nullable();
			$table->decimal('transaction_fee', 16, 4)->nullable();
			$table->decimal('shipping_cost', 16, 4)->nullable();
			$table->boolean('transaction_fee_tax')->default(0);
			$table->boolean('shipping_cost_tax')->default(0);
			$table->integer('order_id')->unsigned();
			$table->integer('previous_status')->nullable();
			$table->decimal('previous_balance', 16, 4)->nullable()->default(0.0000);
			$table->decimal('gateway_fee', 16, 4)->default(0.0000);
			$table->string('voucher_code')->nullable();
			$table->boolean('commission_paid')->default(0);
			$table->dateTime('commission_paid_date')->nullable();
			$table->dateTime('date_cancelled')->nullable();
			$table->boolean('gateway_percentage')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices');
	}

}
