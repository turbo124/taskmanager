<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromocodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promocodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 32)->unique();
			$table->float('reward', 10)->nullable();
			$table->integer('quantity')->nullable();
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->text('data', 65535)->nullable();
			$table->boolean('is_disposable')->default(0);
			$table->dateTime('expires_at')->nullable();
			$table->string('description')->nullable();
			$table->softDeletes();
			$table->enum('amount_type', array('amt','pct','',''))->default('amt');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('promocodes');
	}

}
