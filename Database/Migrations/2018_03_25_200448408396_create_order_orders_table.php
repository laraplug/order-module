<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('shop_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->string('payment_name')->nullable();
            $table->string('payment_postcode')->nullable();
            $table->string('payment_address')->nullable();
            $table->string('payment_address_detail')->nullable();
            $table->string('payment_email')->nullable();
            $table->string('payment_phone')->nullable();

            $table->string('shipping_name')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_address_detail')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();

            $table->text('shipping_custom_field')->nullable();
            $table->text('shipping_note')->nullable();

            $table->integer('total_price');
            $table->integer('total_shipping');
            $table->integer('total_tax');
            $table->integer('total_discount');
            $table->integer('total');

            $table->string('payment_gateway_id');
            $table->string('payment_method_id');

            $table->integer('status_id')->unsigned();

            $table->string('currency_code', 3);
            $table->double('currency_value', 15, 8)->default('1.00000000');

            $table->string('ip', 40)->nullable();
            $table->string('user_agent', 255)->nullable();

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
        Schema::dropIfExists('order__orders');
    }
}
