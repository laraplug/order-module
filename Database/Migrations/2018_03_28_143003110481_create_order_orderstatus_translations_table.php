<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderOrderStatusTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order__order_status_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('description', 255)->nullable();

            $table->integer('order_status_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['order_status_id', 'locale']);
            $table->foreign('order_status_id')->references('id')->on('order__order_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order__order_status_translations', function (Blueprint $table) {
            $table->dropForeign(['order_status_id']);
        });
        Schema::dropIfExists('order__order_status_translations');
    }
}
