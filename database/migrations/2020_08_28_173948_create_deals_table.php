<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('deals', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->string('task_color');
			$table->dateTime('due_date');
			$table->boolean('is_completed')->default(0);
			$table->timestamps();
			$table->integer('is_active')->default(1);
			$table->integer('task_status');
			$table->integer('created_by');
			$table->integer('rating')->unsigned()->nullable();
			$table->integer('customer_id')->unsigned()->index('tasks_customer_id_foreign');
			$table->decimal('valued_at')->nullable();
			$table->integer('source_type')->unsigned()->nullable()->default(1)->index('tasks_source_type_foreign');
			$table->softDeletes();
			$table->integer('assigned_to')->unsigned()->nullable();
			$table->integer('account_id')->unsigned()->index('tasks_account_id_index');
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->smallInteger('task_status_sort_order')->nullable();
			$table->boolean('is_deleted')->default(0);
			$table->text('custom_value3', 65535)->nullable();
			$table->text('custom_value4', 65535)->nullable();
			$table->integer('invoice_id')->unsigned()->nullable()->index('invoice_id');
			$table->integer('user_id')->unsigned()->default(9874)->index('user_id');
			$table->text('public_notes', 65535)->nullable();
			$table->text('private_notes', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('deals');
	}

}
