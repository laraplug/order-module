<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Entities\Transportation;
use Modules\Order\Http\Requests\CreateTransportationRequest;
use Modules\Order\Http\Requests\UpdateTransportationRequest;
use Modules\Order\Repositories\TransportationRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class TransportationController extends AdminBaseController
{
    /**
     * @var TransportationRepository
     */
    private $transportation;

    public function __construct(TransportationRepository $transportation)
    {
        parent::__construct();

        $this->transportation = $transportation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$transportations = $this->transportation->all();

        return view('order::admin.transportations.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('order::admin.transportations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateTransportationRequest $request
     * @return Response
     */
    public function store(CreateTransportationRequest $request)
    {
        $this->transportation->create($request->all());

        return redirect()->route('admin.order.transportation.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('order::transportations.title.transportations')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transportation $transportation
     * @return Response
     */
    public function edit(Transportation $transportation)
    {
        return view('order::admin.transportations.edit', compact('transportation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transportation $transportation
     * @param  UpdateTransportationRequest $request
     * @return Response
     */
    public function update(Transportation $transportation, UpdateTransportationRequest $request)
    {
        $this->transportation->update($transportation, $request->all());

        return redirect()->route('admin.order.transportation.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('order::transportations.title.transportations')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transportation $transportation
     * @return Response
     */
    public function destroy(Transportation $transportation)
    {
        $this->transportation->destroy($transportation);

        return redirect()->route('admin.order.transportation.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('order::transportations.title.transportations')]));
    }
}
