<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
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
        return response()->json([
            'errors' => false,
            'data' => $order->items->map(function($item) {
                $item->product->load('options');
                return $item;
            }),
        ]);
    }

    /**
     * 주문상품 상태저장
     * @param  Order  $order
     * @param  Request $request
     * @return mixed
     */
    public function updateItemStatus(Order $order, Request $request)
    {
        $order->items->where('id', $request->item_id)->first()->update(['status_id' => $request->status_id]);

        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('order::orders.form.status')]),
            'data' => $order,
        ]);
    }

    /**
     * 주문상태 저장
     * @param  Order  $order
     * @param  Request $request
     * @return mixed
     */
    public function updateStatus(Order $order, Request $request)
    {
        $order->status_id = $request->status_id;
        $order->save();

        return response()->json([
            'errors' => false,
            'message' => trans('core::core.messages.resource updated', ['name' => trans('order::orders.title.orders')]),
            'data' => $order,
        ]);
    }
}
