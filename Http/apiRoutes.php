<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/order', 'middleware' => ['bindings', 'api.token', 'auth.admin']], function (Router $router) {

    $router->get('orders/datatable', [
        'as' => 'api.order.orders.datatable',
        'uses' => 'OrderController@datatable',
        'middleware' => 'token-can:order.orders.index',
    ]);

    $router->get('orders/{order}/items', [
        'as' => 'api.order.orders.items.index',
        'uses' => 'OrderController@orderItems',
        'middleware' => 'token-can:order.orders.index',
    ]);

    $router->put('orders/{order}/status', [
        'as' => 'api.order.orders.updateStatus',
        'uses' => 'OrderController@updateStatus',
        'middleware' => 'can:order.orders.edit'
    ]);

    $router->put('orders/{order}/itemstatus', [
        'as' => 'api.order.orders.updateItemStatus',
        'uses' => 'OrderController@updateItemStatus',
        'middleware' => 'can:order.orders.edit'
    ]);

});
