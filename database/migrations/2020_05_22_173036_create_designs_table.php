<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDesignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('designs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('account_id')->unsigned()->nullable()->index();
			$table->string('name');
			$table->boolean('is_custom')->default(1);
			$table->boolean('is_active')->default(1);
			$table->text('design', 65535)->nullable();
			$table->timestamps();
			$table->dateTime('deleted_at');
			$table->boolean('is_deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('designs');
	}

}
