<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Entities\OrderStatus;

use Illuminate\Database\Eloquent\Model;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if(!OrderStatus::find(OrderStatus::PENDING_PAYMENT))
            OrderStatus::create([
                'id' => OrderStatus::PENDING_PAYMENT,
                'code' => 'pending_payment',
                'name' => 'Pending Payment',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::PENDING_PAYMENT_APPROVAL))
            OrderStatus::create([
                'id' => OrderStatus::PENDING_PAYMENT_APPROVAL,
                'code' => 'pending_approval',
                'name' => 'Pending Payment Approval',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::PENDING))
            OrderStatus::create([
                'id' => OrderStatus::PENDING,
                'code' => 'pending',
                'name' => 'Pending',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::PROCESSING))
            OrderStatus::create([
                'id' => OrderStatus::PROCESSING,
                'code' => 'processing',
                'name' => 'Processing',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::SHIPPING))
            OrderStatus::create([
                'id' => OrderStatus::SHIPPING,
                'code' => 'shipping',
                'name' => 'Shipping',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::SHIPPED))
            OrderStatus::create([
                'id' => OrderStatus::SHIPPED,
                'code' => 'shipped',
                'name' => 'Shipped',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::COMPLETED))
            OrderStatus::create([
                'id' => OrderStatus::COMPLETED,
                'code' => 'completed',
                'name' => 'Completed',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::CANCELED))
            OrderStatus::create([
                'id' => OrderStatus::CANCELED,
                'code' => 'canceled',
                'name' => 'Canceled',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::PARTIALLY_CANCELED))
            OrderStatus::create([
                'id' => OrderStatus::PARTIALLY_CANCELED,
                'code' => 'partially_canceled',
                'name' => 'Partially Canceled',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::REFUNDED))
            OrderStatus::create([
                'id' => OrderStatus::REFUNDED,
                'code' => 'refunded',
                'name' => 'Refunded',
                'is_system' => true,
            ]);
        if(!OrderStatus::find(OrderStatus::PARTIALLY_REFUNDED))
            OrderStatus::create([
                'id' => OrderStatus::PARTIALLY_REFUNDED,
                'code' => 'partially_refunded',
                'name' => 'Partially Refunded',
                'is_system' => true,
            ]);

        $this->call(SentinelGroupSeedTableSeeder::class);
    }
}
