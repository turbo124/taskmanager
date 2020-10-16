<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->index();
            $table->unsignedInteger('user_id')->index('quotes_user_id_foreign');
            $table->unsignedInteger('assigned_to')->nullable();
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('recurring_quote_id')->nullable();
            $table->unsignedInteger('design_id')->nullable();
            $table->string('number')->nullable();
            $table->double('discount', 8, 2)->default(0.00);
            $table->tinyInteger('is_amount_discount')->default(0);
            $table->string('po_number')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->text('line_items')->nullable();
            $table->text('footer')->nullable();
            $table->text('public_notes')->nullable();
            $table->text('private_notes')->nullable();
            $table->text('terms')->nullable();
            $table->decimal('sub_total', 16, 4);
            $table->decimal('tax_total', 16, 4);
            $table->decimal('discount_total', 16, 4);
            $table->integer('parent_id')->nullable();
            $table->tinyInteger('uses_inclusive_taxes')->default(0);
            $table->decimal('total', 16, 4);
            $table->decimal('balance', 16, 4);
            $table->decimal('partial', 16, 4)->nullable();
            $table->dateTime('partial_due_date')->nullable();
            $table->dateTime('last_viewed')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('task_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->string('custom_value1')->nullable();
            $table->string('custom_value2')->nullable();
            $table->string('custom_value3')->nullable();
            $table->string('custom_value4')->nullable();
            $table->date('last_sent_date')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->decimal('tax_rate', 13, 3)->nullable()->default(0.000);
            $table->string('tax_rate_name')->nullable();
            $table->decimal('transaction_fee', 16, 4)->nullable();
            $table->decimal('shipping_cost', 16, 4)->nullable();
            $table->tinyInteger('transaction_fee_tax')->default(0);
            $table->tinyInteger('shipping_cost_tax')->default(0);
            $table->unsignedInteger('order_id')->nullable();
            $table->decimal('gateway_fee', 16, 4)->default(0.0000);
            $table->tinyInteger('gateway_percentage')->default(0);
            $table->dateTime('date_reminder_last_sent')->nullable();
            $table->integer('currency_id')->nullable();
            $table->decimal('exchange_rate', 12)->default(0.00);
            $table->tinyInteger('gateway_fee_applied')->default(0);
            $table->date('start_date')->nullable();
            $table->date('recurring_due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
