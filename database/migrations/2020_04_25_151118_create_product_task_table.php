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
			$table->integer('task_id')->unsigned()->index('product_task_task_id_foreign');
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
			$table->decimal('tax_rate', 13, 3);
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
			$table->string('custom_surcharge1')->nullable();
			$table->string('custom_surcharge2')->nullable();
			$table->string('custom_surcharge3')->nullable();
			$table->string('custom_surcharge4')->nullable();
			$table->boolean('custom_surcharge_tax1')->default(0);
			$table->boolean('custom_surcharge_tax2')->default(0);
			$table->boolean('custom_surcharge_tax3')->default(0);
			$table->boolean('custom_surcharge_tax4')->default(0);
			$table->string('number')->nullable();
			$table->softDeletes();
			$table->integer('quote_id')->unsigned()->nullable();
			$table->integer('invoice_id')->unsigned()->nullable();
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
