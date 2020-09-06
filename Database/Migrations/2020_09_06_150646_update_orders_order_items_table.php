<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersOrderItemsTable extends Migration
{
    /**세금계산용 칼럼 추가 20200906 Ho
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //총 공급가액, 총 부가가치세, 총 면세 추가
        Schema::table('order__orders',function(Blueprint $table){
            $table->integer('total_supply_amount');
            $table->integer('total_tax_free_amount');
            $table->renameColumn('total_tax','total_tax_amount')->change();
        });
        //총 공급가액, 총 부가가치세, 총 면세 추가
        Schema::table('order__order_items',function(Blueprint $table){
            $table->integer('supply_amount');
            $table->integer('tax_free_amount');
            $table->renameColumn('tax','tax_amount')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order__order_items',function(Blueprint $table){
            $table->dropColumn('supply_amount');
            $table->dropColumn('tax_free_amount');
            $table->renameColumn('tax_amount','tax')->change();
        });
        Schema::table('order__orders',function(Blueprint $table){
            $table->dropColumn('total_supply_amount');
            $table->dropColumn('total_tax_free_amount');
            $table->renameColumn('total_tax_amount','total_tax')->change();
        });

    }
}
