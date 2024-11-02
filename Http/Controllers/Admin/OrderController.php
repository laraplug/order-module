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
// 일반 상품일 경우에만 order_items 를 추려서 정리
    function findValueByKey ($items,string $searchKey) {
        $return="";
        $length = strlen( $searchKey );
        foreach ($items as $key => $value){
            if(substr( $key, 0, $length ) === $searchKey){
                $return .= $value;
            }
        }
        return $return;
    }
    function getItemName ($items, string $name){
        $return = $name;
        foreach ($items as $key=>$value) {
            $return .="\n $key : $value";
        }
        return $return;
    }
    function getAcademyName ($academy_id)
    {
        switch ($academy_id){
            case 1 : return "gdbn";
            case 2 : return  "제니스키즈";
            case 3 : return "자이키즈";
            case 4 : return  "자이영재";
            case 5 : return  "아이파크";
            case 6 : return  "별두리";
            case 7 : return  "푸르지오";
            case 8 : return  "EnRe";
            case 9 : return  "테스트";
            case 10 : return  "원광";
            case 11 : return  "상떼빌키즈";
            default : return "";
        }
    }    /**
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
                ]);
                $exportExcel = [];
                foreach ($order as $item){
                    $orderItems = $item->items;
                    if(count($orderItems)){
                    $items = $item->items[0];

                    $type = $items->product->type;

                    //basic 일 경우 상세정보 추가
                    if($type === 'basic'){
                        $itemLen = count($orderItems);
                        $lastI = 1;

                        for($i = 0; $i < $itemLen;$i=$i+1){
                            $optionValues = $orderItems[$i];
                            $fullPrice =  number_format($item->total_price);
                            $curPrice = number_format($optionValues->price);
                            //UtilityMallExcel Export Test
                            $exportExcel[] = [
                                'id' => $item->id,
                                '이름' => $this->getItemName($optionValues->option_values,$optionValues->product->translations[0]->name),
                                '주문자명' => $item->payment_name,
                                '결제금액' => "$curPrice($fullPrice)",
                                '결제수단' => $item->payment_method_id == 'direct_bank' ? '무통장 입금' : '카드',
                                '주문상태' => $item->status->name,
                                '주문날짜' => $item->created_at,
                                '원아명' => $this->findValueByKey($optionValues->option_values, 'student_name'),
                                '사이즈' => $this->findValueByKey($optionValues->option_values, 'select-size'),
                                '원ID' => $this->findValueByKey($optionValues->option_values, 'academy_select'),
                                '수량' => $optionValues->quantity,
                                '원명' => ""

                            ];
                            $lastI = $lastI +1;
                        };
                    }else{
                        $exportExcel[] = [
                            'id' => $item->id,
                            '이름' => $item->name,
                            '주문자명' => $item->payment_name,
                            '결제금액' => number_format($item->total_price),
                            '결제수단' => $item->payment_method_id == 'direct_bank' ? '무통장 입금' : '카드',
                            '주문상태' => $item->status->name,
                            '주문날짜' => $item->created_at,
                            '원아명' => $this->findValueByKey($items->option_values, 'student_name'),
                            '사이즈' => $this->findValueByKey($items->option_values, 'select-size'),
                            '원ID' => $this->findValueByKey($items->option_values, 'academy_select'),
                            '수량' => $item->quantity,
                            '원명' => $this->getAcademyName($this->findValueByKey($items->option_values, 'academy_id'));

                        ];
                    }
                }
                };
                //기존 order To Excel 기본 아이템에 대한 취합이 필요하여 변경 2024.03.15 Ho
//                $orderToExcel =
//                    $order->map(function($order) use ($testDump) {
//                    $items = $order->items[0];
//                    $orderItems = $order->items;
//                    $type = $items->product->type;
//                        $result = [
//                            'id' => $order->id,
//                            '이름' => $order->name,
//                            '주문자명' => $order->payment_name,
//                            '결제금액' => number_format($order->total_price),
//                            '결제수단' => $order->payment_method_id == 'direct_bank' ? '무통장 입금' : '카드',
//                            '주문상태' => $order->status->name,
//                            '주문날짜' => $order->created_at,
//                            '원아명' => $this->findValueByKey($items->option_values, 'student_name'),
//                            '사이즈' => $this->findValueByKey($items->option_values, 'select-size'),
//                            '원ID' => $this->findValueByKey($items->option_values, 'academy_select'),
//                        ];
//                        array_push($testDump['items'], $result);
//                        var_dump($testDump);
//                        return $testDump;
//                });

//                var_dump($orderToExcel);
//                var_dump($exportExcel);
                $sheet->fromArray($exportExcel,null,'A3');
            });
        })
            ->download('xlsx');
//        openedWindow.close();
    }
}
