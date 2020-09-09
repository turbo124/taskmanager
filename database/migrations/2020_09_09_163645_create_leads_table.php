<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('job_title')->nullable();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('source_type')->unsigned()->index('source_type');
			$table->integer('task_status')->unsigned()->default(5);
			$table->string('address_1')->nullable();
			$table->string('address_2')->nullable();
			$table->string('zip')->nullable();
			$table->string('city')->nullable();
			$table->string('website')->nullable();
			$table->text('public_notes', 65535)->nullable();
			$table->text('private_notes', 65535)->nullable();
			$table->string('name');
			$table->string('description');
			$table->string('phone', 100);
			$table->string('email');
			$table->decimal('valued_at')->default(0.00);
			$table->string('company_name', 100)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('is_deleted')->default(0);
			$table->integer('assigned_to')->unsigned()->nullable()->index('assigned_user_id');
			$table->string('custom_value1')->nullable();
			$table->string('custom_value2')->nullable();
			$table->string('custom_value3')->nullable();
			$table->string('custom_value4')->nullable();
			$table->integer('country_id')->default(225);
			$table->integer('industry_id')->unsigned()->nullable()->index('industry_id');
			$table->integer('status_id');
			$table->string('number')->nullable();
			$table->date('due_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leads');
	}

}
