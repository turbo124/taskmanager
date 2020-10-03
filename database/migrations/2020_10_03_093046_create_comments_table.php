<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('comment');
			$table->integer('user_id')->unsigned()->index('comments_user_id_foreign');
			$table->timestamps(10);
			$table->integer('is_active')->default(1);
			$table->integer('parent_id')->nullable();
			$table->integer('parent_type')->default(1);
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->string(''commentable_type'', 100);
			$table->integer('commentable_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
