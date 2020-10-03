<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->text('permissions')->nullable();
			$table->text('settings')->nullable();
			$table->boolean('is_owner')->default(0);
			$table->boolean('is_admin')->default(0);
			$table->boolean('is_locked')->default(0);
			$table->timestamps(10);
			$table->softDeletes();
			$table->string(''slack_webhook_url'', 200)->nullable();
			$table->text('notifications')->nullable();
			$table->enum(''default_notification_type'', array('mail','slack','',''))->default('mail');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account_user');
	}

}
