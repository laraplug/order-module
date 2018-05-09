<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('order_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->string('payment_method_id');
            $table->string('gateway_id');
            $table->string('gateway_transaction_id')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->integer('amount');
            $table->string('message', 500)->nullable();

            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->text('additional_data')->nullable();

            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order__transactions');
    }
}
