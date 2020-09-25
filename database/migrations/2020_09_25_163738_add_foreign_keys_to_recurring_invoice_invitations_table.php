<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecurringInvoiceInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recurring_invoice_invitations', function(Blueprint $table)
		{
			$table->foreign('recurring_invoice_id', 'recurring_invoice_invitations_ibfk_1')->references('id')->on('recurring_invoices')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recurring_invoice_invitations', function(Blueprint $table)
		{
			$table->dropForeign('recurring_invoice_invitations_ibfk_1');
		});
	}

}
