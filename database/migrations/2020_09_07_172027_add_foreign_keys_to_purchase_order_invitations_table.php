<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPurchaseOrderInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchase_order_invitations', function(Blueprint $table)
		{
			$table->foreign('purchase_order_id', 'purchase_order_invitations_ibfk_1')->references('id')->on('purchase_orders')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('contact_id', 'purchase_order_invitations_ibfk_2')->references('id')->on('company_contacts')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchase_order_invitations', function(Blueprint $table)
		{
			$table->dropForeign('purchase_order_invitations_ibfk_1');
			$table->dropForeign('purchase_order_invitations_ibfk_2');
		});
	}

}
