<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecurringInvoiceInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recurring_invoice_invitations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('invoice_invitations_account_id_foreign');
			$table->integer('user_id')->unsigned()->index('invoice_invitations_user_id_foreign');
			$table->integer('contact_id')->unsigned();
			$table->integer('recurring_invoice_id')->unsigned()->index('invoice_invitations_invoice_id_index');
			$table->string('key')->index('invoice_invitations_key_index');
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
			$table->index(['deleted_at','recurring_invoice_id'], 'invoice_invitations_deleted_at_invoice_id_index');
			$table->unique(['contact_id','recurring_invoice_id'], 'invoice_invitations_customer_id_invoice_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recurring_invoice_invitations');
	}

}
