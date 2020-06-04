<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/order'], function (Router $router) {
    $router->bind('order', function ($id) {

        return app('Modules\Order\Repositories\OrderRepository')->find($id);
    });
    $router->get('orders', [
        'as' => 'admin.order.order.index',
        'uses' => 'OrderController@index',
        'middleware' => 'can:order.orders.index'
    ]);
    $router->get('orders/excel', [
        'as' => 'admin.order.order.excel',
        'uses' => 'OrderController@exportExcel',
    ]);

    $router->get('orders/{order}', [
        'as' => 'admin.order.order.show',
        'uses' => 'OrderController@show',
        'middleware' => 'can:order.orders.index'
    ]);
    $router->get('orders/create', [
        'as' => 'admin.order.order.create',
        'uses' => 'OrderController@create',
        'middleware' => 'can:order.orders.create'
    ]);
    $router->post('orders', [
        'as' => 'admin.order.order.store',
        'uses' => 'OrderController@store',
        'middleware' => 'can:order.orders.create'
    ]);
    $router->get('orders/{order}/edit', [
        'as' => 'admin.order.order.edit',
        'uses' => 'OrderController@edit',
        'middleware' => 'can:order.orders.edit'
    ]);
    $router->put('orders/{order}', [
        'as' => 'admin.order.order.update',
        'uses' => 'OrderController@update',
        'middleware' => 'can:order.orders.edit'
    ]);
    $router->delete('orders/{order}', [
        'as' => 'admin.order.order.destroy',
        'uses' => 'OrderController@destroy',
        'middleware' => 'can:order.orders.destroy'
    ]);


    $router->bind('orderstatus', function ($id) {
        return app('Modules\Order\Repositories\OrderStatusRepository')->find($id);
    });
    $router->get('orderstatuses', [
        'as' => 'admin.order.orderstatus.index',
        'uses' => 'OrderStatusController@index',
        'middleware' => 'can:order.orderstatuses.index'
    ]);
    $router->get('orderstatuses/create', [
        'as' => 'admin.order.orderstatus.create',
        'uses' => 'OrderStatusController@create',
        'middleware' => 'can:order.orderstatuses.create'
    ]);
    $router->post('orderstatuses', [
        'as' => 'admin.order.orderstatus.store',
        'uses' => 'OrderStatusController@store',
        'middleware' => 'can:order.orderstatuses.create'
    ]);
    $router->get('orderstatuses/{orderstatus}/edit', [
        'as' => 'admin.order.orderstatus.edit',
        'uses' => 'OrderStatusController@edit',
        'middleware' => 'can:order.orderstatuses.edit'
    ]);
    $router->put('orderstatuses/{orderstatus}', [
        'as' => 'admin.order.orderstatus.update',
        'uses' => 'OrderStatusController@update',
        'middleware' => 'can:order.orderstatuses.edit'
    ]);
    $router->delete('orderstatuses/{orderstatus}', [
        'as' => 'admin.order.orderstatus.destroy',
        'uses' => 'OrderStatusController@destroy',
        'middleware' => 'can:order.orderstatuses.destroy'
    ]);

    $router->bind('transaction', function ($id) {
        return app('Modules\Order\Repositories\TransactionRepository')->find($id);
    });
    $router->get('transactions', [
        'as' => 'admin.order.transaction.index',
        'uses' => 'TransactionController@index',
        'middleware' => 'can:order.transactions.index'
    ]);
    $router->get('orders/{order}/transactions/create', [
        'as' => 'admin.order.transaction.create',
        'uses' => 'TransactionController@create',
        'middleware' => 'can:order.transactions.create'
    ]);
    $router->post('orders/{order}/transactions', [
        'as' => 'admin.order.transaction.store',
        'uses' => 'TransactionController@store',
        'middleware' => 'can:order.transactions.create'
    ]);
    $router->get('transactions/{transaction}/edit', [
        'as' => 'admin.order.transaction.edit',
        'uses' => 'TransactionController@edit',
        'middleware' => 'can:order.transactions.edit'
    ]);
    $router->put('transactions/{transaction}', [
        'as' => 'admin.order.transaction.update',
        'uses' => 'TransactionController@update',
        'middleware' => 'can:order.transactions.edit'
    ]);
    $router->delete('transactions/{transaction}', [
        'as' => 'admin.order.transaction.destroy',
        'uses' => 'TransactionController@destroy',
        'middleware' => 'can:order.transactions.destroy'
    ]);
    $router->bind('transportation', function ($id) {
        return app('Modules\Order\Repositories\TransportationRepository')->find($id);
    });
    $router->get('transportations', [
        'as' => 'admin.order.transportation.index',
        'uses' => 'TransportationController@index',
        'middleware' => 'can:order.transportations.index'
    ]);
    $router->get('transportations/create', [
        'as' => 'admin.order.transportation.create',
        'uses' => 'TransportationController@create',
        'middleware' => 'can:order.transportations.create'
    ]);
    $router->post('transportations', [
        'as' => 'admin.order.transportation.store',
        'uses' => 'TransportationController@store',
        'middleware' => 'can:order.transportations.create'
    ]);
    $router->get('transportations/{transportation}/edit', [
        'as' => 'admin.order.transportation.edit',
        'uses' => 'TransportationController@edit',
        'middleware' => 'can:order.transportations.edit'
    ]);
    $router->put('transportations/{transportation}', [
        'as' => 'admin.order.transportation.update',
        'uses' => 'TransportationController@update',
        'middleware' => 'can:order.transportations.edit'
    ]);
    $router->delete('transportations/{transportation}', [
        'as' => 'admin.order.transportation.destroy',
        'uses' => 'TransportationController@destroy',
        'middleware' => 'can:order.transportations.destroy'
    ]);
// append




});
