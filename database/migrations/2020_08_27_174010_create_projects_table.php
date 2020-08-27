<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('description', 65535);
			$table->boolean('is_completed')->default(0);
			$table->timestamps();
			$table->integer('customer_id')->nullable();
			$table->integer('account_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->integer('assigned_to')->unsigned()->nullable();
			$table->text('private_notes', 65535)->nullable();
			$table->date('due_date')->nullable();
			$table->float('budgeted_hours', 10, 0)->nullable();
			$table->softDeletes();
			$table->boolean('is_deleted')->default(0);
			$table->float('task_rate', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
