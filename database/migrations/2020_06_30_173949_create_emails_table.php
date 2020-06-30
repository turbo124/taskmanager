<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emails', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('recipient', 100);
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->string('subject');
			$table->text('body', 65535);
			$table->string('entity', 100);
			$table->integer('entity_id');
			$table->string('direction', 20);
			$table->date('sent_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('recipient_email', 100);
			$table->text('design', 65535)->nullable();
			$table->boolean('failed_to_send')->default(0);
			$table->integer('number_of_tries')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('emails');
	}

}
