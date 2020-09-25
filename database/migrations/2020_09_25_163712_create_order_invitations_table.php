<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_invitations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('account_id');
			$table->integer('contact_id')->unsigned()->index('client_contact_id');
			$table->integer('order_id')->unsigned()->index('order_id');
			$table->string('key');
			$table->string('transaction_reference')->nullable();
			$table->string('message_id')->nullable();
			$table->text('email_error', 65535)->nullable();
			$table->text('signature_base64', 65535)->nullable();
			$table->dateTime('signature_date')->nullable();
			$table->dateTime('sent_date')->nullable();
			$table->dateTime('viewed_date')->nullable();
			$table->dateTime('opened_date')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('user_id')->unsigned()->index('user_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_invitations');
	}

}
