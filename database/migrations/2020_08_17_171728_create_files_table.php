<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('files_user_id_foreign');
			$table->timestamps();
			$table->integer('is_active')->default(1);
			$table->string('file_path');
			$table->integer('account_id')->unsigned()->index();
			$table->string('preview')->nullable();
			$table->string('name')->nullable();
			$table->string('type')->nullable();
			$table->integer('size')->unsigned()->nullable();
			$table->integer('width')->unsigned()->nullable();
			$table->integer('height')->unsigned()->nullable();
			$table->boolean('is_default')->default(0);
			$table->integer('fileable_id')->unsigned();
			$table->string('fileable_type');
			$table->integer('company_id')->unsigned()->nullable();
			$table->softDeletes();
			$table->integer('assigned_to')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
