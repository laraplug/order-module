<?php

namespace Modules\Order\Entities;

use Modules\Shop\Contracts\ShopTransportationInterface;
use Modules\Shop\Repositories\ShippingMethodManager;
use Modules\Shop\Repositories\ShippingGatewayManager;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class Transportation extends Model implements ShopTransportationInterface
{
    protected $table = 'order__transportations';
    protected $fillable = [
        'user_id',
        'shipping_method_id',
        'gateway_id',
        'gateway_transportation_id',
        'currency_code',
        'fee',
        'message',
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
        return app(ShippingGatewayManager::class)->find($this->gateway_id);
    }

    public function getPaymentMethodAttribute()
    {
        return app(ShippingMethodManager::class)->find($this->shipping_method_id);
    }

}
