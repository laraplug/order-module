<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__transportations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('order_id')->nullable();
            $table->integer('user_id')->nullable();

            $table->string('shipping_method_id');
            $table->string('gateway_id');
            $table->string('gateway_transportation_id')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->integer('fee');
            $table->string('message', 1024)->nullable();

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
        Schema::dropIfExists('order__transportations');
    }
}
