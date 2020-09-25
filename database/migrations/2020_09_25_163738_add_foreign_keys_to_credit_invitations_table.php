<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCreditInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('credit_invitations', function(Blueprint $table)
		{
			$table->foreign('account_id')->references('id')->on('accounts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('credit_id')->references('id')->on('credits')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('contact_id', 'credit_invitations_ibfk_1')->references('id')->on('customer_contacts')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
		Schema::table('credit_invitations', function(Blueprint $table)
		{
			$table->dropForeign('credit_invitations_account_id_foreign');
			$table->dropForeign('credit_invitations_credit_id_foreign');
			$table->dropForeign('credit_invitations_ibfk_1');
			$table->dropForeign('credit_invitations_user_id_foreign');
		});
	}

}