<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Http\Requests\CreateOrderStatusRequest;
use Modules\Order\Http\Requests\UpdateOrderStatusRequest;
use Modules\Order\Repositories\OrderStatusRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class OrderStatusController extends AdminBaseController
{
    /**
     * @var OrderStatusRepository
     */
    private $orderstatus;

    public function __construct(OrderStatusRepository $orderstatus)
    {
        parent::__construct();

        $this->orderstatus = $orderstatus;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orderstatuses = $this->orderstatus->all();

        return view('order::admin.orderstatuses.index', compact('orderstatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('order::admin.orderstatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderStatusRequest $request
     * @return Response
     */
    public function store(CreateOrderStatusRequest $request)
    {
        $this->orderstatus->create($request->all());

        return redirect()->route('admin.order.orderstatus.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('order::orderstatuses.title.orderstatuses')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  OrderStatus $orderstatus
     * @return Response
     */
    public function edit(OrderStatus $orderstatus)
    {
        return view('order::admin.orderstatuses.edit', compact('orderstatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderStatus $orderstatus
     * @param  UpdateOrderStatusRequest $request
     * @return Response
     */
    public function update(OrderStatus $orderstatus, UpdateOrderStatusRequest $request)
    {
        $this->orderstatus->update($orderstatus, $request->all());

        return redirect()->route('admin.order.orderstatus.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('order::orderstatuses.title.orderstatuses')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderStatus $orderstatus
     * @return Response
     */
    public function destroy(OrderStatus $orderstatus)
    {
        $this->orderstatus->destroy($orderstatus);

        return redirect()->route('admin.order.orderstatus.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('order::orderstatuses.title.orderstatuses')]));
    }
}
