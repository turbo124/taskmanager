<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttributeValueProductAttributeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attribute_value_product_attribute', function(Blueprint $table)
		{
			$table->integer('attribute_value_id')->unsigned()->index('attribute_value_product_attribute_attribute_value_id_foreign');
			$table->integer('product_attribute_id')->unsigned()->index('attribute_value_product_attribute_product_attribute_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('attribute_value_product_attribute');
	}

}
