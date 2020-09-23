<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecurringQuoteInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recurring_quote_invitations', function(Blueprint $table)
		{
			$table->foreign('recurring_quote_id', 'recurring_quote_invitations_ibfk_1')->references('id')->on('recurring_quotes')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recurring_quote_invitations', function(Blueprint $table)
		{
			$table->dropForeign('recurring_quote_invitations_ibfk_1');
		});
	}

}
