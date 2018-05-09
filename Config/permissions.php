<?php

return [
    'order.orders' => [
        'index' => 'order::orders.list resource',
        'create' => 'order::orders.create resource',
        'edit' => 'order::orders.edit resource',
        'destroy' => 'order::orders.destroy resource',
    ],
    'order.orderstatuses' => [
        'index' => 'order::orderstatuses.list resource',
        'create' => 'order::orderstatuses.create resource',
        'edit' => 'order::orderstatuses.edit resource',
        'destroy' => 'order::orderstatuses.destroy resource',
    ],
    'order.transactions' => [
        'index' => 'order::transactions.list resource',
        'create' => 'order::transactions.create resource',
        'edit' => 'order::transactions.edit resource',
        'destroy' => 'order::transactions.destroy resource',
    ],
    'order.transportations' => [
        'index' => 'order::transportations.list resource',
        'create' => 'order::transportations.create resource',
        'edit' => 'order::transportations.edit resource',
        'destroy' => 'order::transportations.destroy resource',
    ],
// append




];
