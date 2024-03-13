<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Order\Entities\OrderStatus;
use Modules\Shop\Facades\Shop;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\OrderStatusRepository;

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

    /**
     * @var OrderStatusRepository
     */
    private $orderstatus;

    public function __construct(OrderRepository $order, OrderStatusRepository $orderstatus)
    {
        parent::__construct();

        $this->order = $order;
        $this->orderstatus = $orderstatus;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('order::admin.orders.index');
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
        $orderStatuses = $this->orderstatus->all()->pluck('name', 'id')->toArray();

        return view('order::admin.orders.view', compact('order', 'paymentMethods', 'orderStatuses'));
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


    /**
     * @param Request $request
     * @return Response
     */
    public function exportExcel(Request $request){
        $startDay =(!$request->startDay)? 0:$request->startDay;
        $endDay = (!$request->endDay)? date("Y-m-d",time()+86400):$request->endDay;
        $order = $this->order->allWithBuilder()->whereBetween('created_at',[$startDay,$endDay])->get();
        $title = '주문내역';
        Excel::create($title,function($excel) use ($order,$startDay,$endDay){
            $excel->sheet('주문내역', function($sheet) use($order,$startDay,$endDay){
                $sheet->mergeCells('A1:G1');
                $sheet->setHeight(1, 40);
                $sheet->cell('A1', function ($cell) use ($startDay, $endDay) {
                    ($startDay == 0)? $startDay = "처음": $startDay;
                    $cell->setValue("{$startDay} ~  {$endDay} 주문내역");
                    $cell->setFontSize(20);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });
                $sheet->setWidth([
                    'A' => 5,
                    'B' => 20,
                    'C' => 20,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 100,
                ]);

                $orderToExcel = $order->map(function($order){
                    $items = $order->items[0];
                // 일반 상품일 경우에만 order_items 를 추려서 정리
                    $itemVal="";
                    if($items->product->type =='basic'){
                        foreach ($items->option_values as $key => $value){
                            $itemVal = "$key : $value ";
                        }
//                        $itemVal = $items->option_values->map(function($optionvalues) {
//                            return $optionvalues;
//                        });
                    };
                    $result = [
                        'id' => $order->id,
                        '이름' => $order->name,
                        '주문자명' => $order->payment_name,
                        '결제금액' => number_format($order->total_price),
                        '결제수단' => $order->payment_method_id == 'direct_bank' ? '무통장 입금' : '카드',
                        '주문상태' => $order->status->name,
                        '주문날짜' => $order->created_at,
                        '옵션'=>$itemVal
                    ];
                    return $result;
                });
                $sheet->fromArray($orderToExcel,null,'A3');
            });
        })->download('xlsx');
        openedWindow.close();
    }
}
