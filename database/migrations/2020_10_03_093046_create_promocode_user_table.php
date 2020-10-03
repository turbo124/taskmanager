<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promocode_user', function(Blueprint $table)
		{
			$table->integer('customer_id')->unsigned()->index('customer_id');
			$table->integer('promocode_id')->unsigned()->index('promocode_user_promocode_id_foreign');
			$table->timestamp('used_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('order_id')->unsigned()->index('order_id');
			$table->integer(''id'', true);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('promocode_user');
	}

}
