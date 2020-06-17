<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_task', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id')->unsigned()->nullable()->index('product_task_task_id_foreign');
			$table->timestamps();
			$table->integer('user_id');
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('status_id')->unsigned()->default(1)->index('status');
			$table->decimal('balance', 16, 4);
			$table->decimal('tax_total', 16, 4);
			$table->decimal('sub_total', 16, 4);
			$table->decimal('discount_total', 16, 4);
			$table->decimal('total', 16, 4);
			$table->text('public_notes', 65535)->nullable();
			$table->text('private_notes', 65535)->nullable();
			$table->text('terms', 65535)->nullable();
			$table->text('footer', 65535)->nullable();
			$table->text('line_items', 65535)->nullable();
			$table->string('tax_rate_name')->nullable();
			$table->decimal('tax_rate', 13, 3)->nullable();
			$table->date('date');
			$table->decimal('partial', 16, 4)->nullable();
			$table->dateTime('partial_due_date')->nullable();
			$table->integer('customer_id')->unsigned()->index('customer_id');
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->string('custom_value3')->nullable();
			$table->string('custom_value4')->nullable();
			$table->boolean('is_amount_discount')->default(0);
			$table->integer('design_id')->unsigned()->nullable();
			$table->dateTime('due_date')->nullable();
			$table->decimal('transaction_fee', 16, 4)->nullable();
			$table->decimal('shipping_cost', 16, 4)->nullable();
			$table->boolean('transaction_fee_tax')->default(0);
			$table->boolean('shipping_cost_tax')->default(0);
			$table->string('number')->nullable();
			$table->softDeletes();
			$table->integer('quote_id')->unsigned()->nullable();
			$table->integer('invoice_id')->unsigned()->nullable();
			$table->boolean('is_deleted')->default(0);
			$table->string('po_number', 100)->nullable();
			$table->integer('previous_status')->nullable();
			$table->string('shipping_id', 100)->nullable();
			$table->string('shipping_label_url')->nullable();
			$table->string('voucher_code')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_task');
	}

}
