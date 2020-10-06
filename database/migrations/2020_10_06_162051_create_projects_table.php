<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('is_completed')->default(0);
            $table->timestamps();
            $table->integer('customer_id')->nullable();
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('assigned_to')->nullable();
            $table->text('private_notes')->nullable();
            $table->date('due_date')->nullable();
            $table->double('budgeted_hours')->nullable();
            $table->softDeletes();
            $table->tinyInteger('is_deleted')->default(0);
            $table->double('task_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
