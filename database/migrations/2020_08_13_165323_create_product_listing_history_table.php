<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductListingHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_listing_history', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('product_id')->unsigned();
			$table->text('changes', 65535);
			$table->timestamps();
			$table->integer('account_id')->unsigned();
			$table->integer('user_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_listing_history');
	}

}
