<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\Transaction;
use Illuminate\Support\Facades\Auth;
use Modules\Shop\Payments\Methods\DirectBank;
use Modules\Shop\Payments\Gateways\DirectPayGateway;
use Modules\Order\Http\Requests\CreateTransactionRequest;
use Modules\Order\Http\Requests\UpdateTransactionRequest;
use Modules\Order\Repositories\TransactionRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class TransactionController extends AdminBaseController
{
    /**
     * @var TransactionRepository
     */
    private $transaction;

    public function __construct(TransactionRepository $transaction)
    {
        parent::__construct();

        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $transactions = $this->transaction->all();

        return view('order::admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Order $order
     * @return Response
     */
    public function create(Order $order)
    {
        $user = Auth::user();
        // 직접 입력은 무통장입금만 가능
        $paymentMethodId = DirectBank::getId();
        $paymentGatewayId = (new DirectPayGateway)->getId();
        // 미결상태면
        $isWaitingPayment = $order->status_id < OrderStatus::PENDING;
        return view('order::admin.transactions.create', compact('order', 'user', 'paymentMethodId', 'paymentGatewayId', 'isWaitingPayment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateTransactionRequest $request
     * @return Response
     */
    public function store(Order $order, CreateTransactionRequest $request)
    {
        $data = $request->all();
        $data['order_id'] = $order->id;
        $this->transaction->create($data);

        if($request->order_paid) {
            $order->status_id = OrderStatus::PENDING;
            $order->save();
        }

        return redirect()->route('admin.order.order.show', $order->id)
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('order::transactions.title.transactions')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Transaction $transaction
     * @return Response
     */
    public function edit(Transaction $transaction)
    {
        return view('order::admin.transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Transaction $transaction
     * @param  UpdateTransactionRequest $request
     * @return Response
     */
    public function update(Transaction $transaction, UpdateTransactionRequest $request)
    {
        $this->transaction->update($transaction, $request->all());

        return redirect()->route('admin.order.order.show', $transaction->order_id)
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('order::transactions.title.transactions')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transaction $transaction
     * @return Response
     */
    public function destroy(Transaction $transaction)
    {
        $this->transaction->destroy($transaction);

        return redirect()->route('admin.order.order.show', $transaction->order_id)
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('order::transactions.title.transactions')]));
    }
}
