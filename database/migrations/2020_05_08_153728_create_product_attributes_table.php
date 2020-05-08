<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_attributes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('quantity');
			$table->decimal('price')->nullable();
			$table->integer('product_id')->unsigned()->index('product_attributes_product_id_foreign');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_attributes');
	}

}
