<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Shop\Facades\Shop;

use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\CreateOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Repositories\OrderRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class OrderController extends AdminBaseController
{
    /**
     * @var OrderRepository
     */
    private $order;

    public function __construct(OrderRepository $order)
    {
        parent::__construct();

        $this->order = $order;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = $this->order->all();

        return view('order::admin.orders.index', compact('orders'));
    }

    /**
     * Display a resource.
     *
     * @param Order $order
     * @return Response
     */
    public function show(Order $order)
    {
        Shop::instance($order->shop_id);
        $paymentMethods = Shop::getPaymentMethods()->flatten()->mapWithKeys(function($method) {
            return [$method::getId() => $method::getName()];
        })->all();
        return view('order::admin.orders.view', compact('order', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('order::admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderRequest $request
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $this->order->create($request->all());

        return redirect()->route('admin.order.order.show', $order->id)
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('order::orders.title.orders')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Order $order
     * @return Response
     */
    public function edit(Order $order)
    {
        Shop::instance($order->shop_id);
        $paymentMethods = Shop::getPaymentMethods()->flatten()->mapWithKeys(function($method) {
            return [$method::getId() => $method::getName()];
        })->all();
        return view('order::admin.orders.edit', compact('order', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Order $order
     * @param  UpdateOrderRequest $request
     * @return Response
     */
    public function update(Order $order, UpdateOrderRequest $request)
    {
        $this->order->update($order, $request->all());

        return redirect()->route('admin.order.order.show', $order->id)
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('order::orders.title.orders')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Order $order
     * @return Response
     */
    public function destroy(Order $order)
    {
        $this->order->destroy($order);

        return redirect()->route('admin.order.order.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('order::orders.title.orders')]));
    }
}
