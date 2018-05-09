<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('order_id')->unsigned();

            $table->integer('shop_id')->unsigned();
            $table->integer('product_id')->unsigned();

            // For bundle product
            $table->integer('parent_id')->unsigned();
            $table->integer('price')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->integer('tax')->unsigned();
            $table->integer('discount')->unsigned();
            $table->integer('total')->unsigned();
            $table->string('note', 500)->nullable();

            // Shipping
            $table->string('shipping_method_id')->nullable();
            $table->integer('shipping_storage_id')->unsigned();

            $table->integer('status_id')->unsigned();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('order__orders')->onDelete('cascade');
        });

        Schema::create('order__order_item_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('order_item_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('slug');
            $table->text('value');

            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order__order_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order__order_item_options');
        Schema::dropIfExists('order__order_items');
    }
}
