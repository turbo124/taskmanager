<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoiceInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_invitations', function(Blueprint $table)
		{
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('contact_id', 'invoice_invitations_ibfk_1')->references('id')->on('customer_contacts')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_invitations', function(Blueprint $table)
		{
			$table->dropForeign('invoice_invitations_account_id_foreign');
			$table->dropForeign('invoice_invitations_ibfk_1');
			$table->dropForeign('invoice_invitations_invoice_id_foreign');
			$table->dropForeign('invoice_invitations_user_id_foreign');
		});
	}

}
