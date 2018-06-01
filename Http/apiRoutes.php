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

});
