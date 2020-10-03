<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invitations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index('quote_invitations_account_id_foreign');
			$table->integer('user_id')->unsigned()->index('quote_invitations_user_id_foreign');
			$table->integer('contact_id')->unsigned()->index('quote_invitations_customer_id_foreign');
			$table->integer('inviteable_id')->unsigned()->index('quote_invitations_quote_id_index');
			$table->string('key')->index('quote_invitations_key_index');
			$table->text('signature_base64')->nullable();
			$table->dateTime('signature_date')->nullable();
			$table->dateTime('sent_date')->nullable();
			$table->dateTime('viewed_date')->nullable();
			$table->dateTime('opened_date')->nullable();
			$table->timestamps(10);
			$table->softDeletes();
			$table->text('client_signature')->nullable();
			$table->string(''inviteable_type'', 100);
			$table->index('['deleted_at','inviteable_id']', 'quote_invitations_deleted_at_quote_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invitations');
	}

}
