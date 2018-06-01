<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\OrderRepository;
use Yajra\DataTables\Facades\DataTables;

/**
 * @resource Order
 */
class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $order;

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    /**
     * GET datatable()
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        return DataTables::eloquent($this->order->allWithBuilder())->make(true);
    }

    /**
     * 주문상품목록
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
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
