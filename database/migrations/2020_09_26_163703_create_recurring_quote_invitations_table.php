<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecurringQuoteInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recurring_quote_invitations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('quote_invitations_account_id_foreign');
			$table->integer('user_id')->unsigned()->index('quote_invitations_user_id_foreign');
			$table->integer('contact_id')->unsigned()->index('quote_invitations_customer_id_foreign');
			$table->integer('recurring_quote_id')->unsigned()->index('quote_invitations_quote_id_index');
			$table->string('key')->index('quote_invitations_key_index');
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
			$table->text('client_signature', 65535)->nullable();
			$table->index(['deleted_at','recurring_quote_id'], 'quote_invitations_deleted_at_quote_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recurring_quote_invitations');
	}

}
