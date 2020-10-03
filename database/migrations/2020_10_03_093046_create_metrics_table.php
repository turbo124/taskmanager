<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('metrics', function(Blueprint $table)
		{
			$table->bigInteger(''id'', true)->unsigned();
			$table->string('name');
			$table->string('type');
			$table->float('value')->default(1.00);
			$table->string('resolution')->nullable();
			$table->text('metadata')->nullable();
			$table->timestamps(10);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('metrics');
	}

}
