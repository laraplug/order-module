<?php

namespace Modules\Order\Entities;

use Modules\Shop\Contracts\ShopTransactionInterface;
use Modules\Shop\Repositories\PaymentMethodManager;
use Modules\Shop\Repositories\PaymentGatewayManager;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class Transaction extends Model implements ShopTransactionInterface
{

    protected $table = 'order__transactions';
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'gateway_id',
        'gateway_transaction_id',
        'currency_code',
        'amount',
        'message',
        'pay_at',
        // receipt issue date
        'receipt_at',
        'payer_name',
        'bank_name',
        'bank_account',
        'additional_data',
        'cancelled_at',
        'cancel_reason',
    ];
    protected $casts = [
        'additional_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGatewayAttribute()
    {
        return app(PaymentGatewayManager::class)->find($this->gateway_id);
    }

    public function getPaymentMethodAttribute()
    {
        return app(PaymentMethodManager::class)->find($this->payment_method_id);
    }

}
