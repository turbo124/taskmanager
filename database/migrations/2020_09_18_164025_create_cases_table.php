<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('subject');
			$table->text('message', 65535);
			$table->timestamps();
			$table->softDeletes();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('customer_id')->unsigned()->index('customer_id');
			$table->integer('status_id');
			$table->boolean('is_deleted')->default(0);
			$table->string('number')->nullable();
			$table->text('private_notes', 65535)->nullable();
			$table->integer('category_id')->unsigned()->nullable()->index('category_id');
			$table->integer('priority_id')->nullable();
			$table->date('due_date')->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->integer('assigned_to')->unsigned();
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->string('custom_value3')->nullable();
			$table->string('custom_value4')->nullable();
			$table->integer('contact_id')->unsigned()->nullable();
			$table->dateTime('date_opened')->nullable();
			$table->integer('opened_by')->unsigned()->nullable();
			$table->dateTime('date_closed')->nullable();
			$table->integer('closed_by')->unsigned()->nullable();
			$table->integer('merged_case_id')->unsigned()->nullable();
			$table->boolean('has_merged_case')->default(0);
			$table->integer('link_type')->unsigned();
			$table->integer('link_value')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cases');
	}

}