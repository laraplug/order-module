<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;

/**
 * @resource Order
 */
class OrderController extends Controller
{
    public function orderItems(Order $order)
    {
        $items = $order->items->map(function ($item) {
            $data = $item->toArray();
            $data['options'] = $item->options->keyBy('slug');

            return $data;
        });

        return response()->json([
            'errors' => false,
            'data' => $items,
        ]);
    }
}
