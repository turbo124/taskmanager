<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_invitations', function(Blueprint $table)
		{
			$table->foreign('account_id', 'order_invitations_ibfk_1')->references('id')->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('user_id', 'order_invitations_ibfk_3')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('order_id', 'order_invitations_ibfk_4')->references('id')->on('product_task')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('contact_id', 'order_invitations_ibfk_5')->references('id')->on('client_contacts')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_invitations', function(Blueprint $table)
		{
			$table->dropForeign('order_invitations_ibfk_1');
			$table->dropForeign('order_invitations_ibfk_3');
			$table->dropForeign('order_invitations_ibfk_4');
			$table->dropForeign('order_invitations_ibfk_5');
		});
	}

}
