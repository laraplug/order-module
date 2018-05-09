<?php

namespace Modules\Order\Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SentinelGroupSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Save the permissions
        $group = Sentinel::findRoleBySlug('admin');

        $group->addPermission('order.orders.index');
        $group->addPermission('order.orders.create');
        $group->addPermission('order.orders.edit');
        $group->addPermission('order.orders.destroy');

        $group->addPermission('order.orderstatuses.index');
        $group->addPermission('order.orderstatuses.create');
        $group->addPermission('order.orderstatuses.edit');
        $group->addPermission('order.orderstatuses.destroy');

        $group->addPermission('order.transactions.index');
        $group->addPermission('order.transactions.create');
        $group->addPermission('order.transactions.edit');
        $group->addPermission('order.transactions.destroy');

        $group->addPermission('order.transportations.index');
        $group->addPermission('order.transportations.create');
        $group->addPermission('order.transportations.edit');
        $group->addPermission('order.transportations.destroy');

        $group->save();
    }
}
