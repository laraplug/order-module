<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => '/order', 'middleware' => ['bindings', 'api.token', 'auth.admin']], function (Router $router) {

    $router->get('orders/{order}/items', [
        'as' => 'api.order.orders.items.index',
        'uses' => 'OrderController@orderItems',
        'middleware' => 'token-can:order.orders.index',
    ]);

});
