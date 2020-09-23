<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reviews', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('product_id')->unsigned()->index('product_id');
			$table->integer('rating');
			$table->text('comment', 65535);
			$table->timestamps();
			$table->boolean('spam');
			$table->boolean('approved');
			$table->integer('client_contact_id')->unsigned()->index('client_contact_id');
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reviews');
	}

}
