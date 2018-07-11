<?php

namespace Modules\Order\Entities;

use Modules\Shop\Entities\Shop;

use Modules\Shop\Contracts\ShopItemInterface;
use Modules\Shop\Contracts\ShopOrderInterface;
use Modules\Shop\Repositories\PaymentMethodManager;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Sentinel\User;

class Order extends Model implements ShopOrderInterface
{

    protected $table = 'order__orders';
    protected $fillable = [
        'payment_name',
        'payment_postcode',
        'payment_address',
        'payment_address_detail',
        'payment_email',
        'payment_phone',

        'shipping_name',
        'shipping_postcode',
        'shipping_address',
        'shipping_address_detail',
        'shipping_email',
        'shipping_phone',

        'shipping_custom_field',
        'shipping_note',

        'total_price',
        'total_shipping',
        'total_tax',
        'total_discount',
        'total',

        'payment_gateway_id',
        'payment_method_id',

        'status_id',
        'currency_code',
        'currency_value',

        'ip',
        'user_agent',

        // Virtual
        'items',
    ];
    protected $appends = [
        'shop_name',
        'name',
        'status',
        'currency',
    ];

    /**
     * @inheritDoc
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        return $this->belongsTo(User::class)->with('profile');
    }

    /**
     * @inheritDoc
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @inheritDoc
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    /**
     * @inheritDoc
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @inheritDoc
     */
    public function transportations()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * 주문상품 저장
     * Save Ordered Items
     */
     public function setItemsAttribute($items)
     {
         static::saved(function ($model) use ($items) {
             $savedIds = [];
             foreach ($items as $data) {
                 // 상품ID는 필수
                 if (empty(array_filter($data)) || empty($data['product_id'])) {
                     continue;
                 }

                 // 이미 존재하면 저장, 없으면 생성
                 if(!empty($data['id']) && $orderItem = $this->items()->find($data['id'])) {
                     $orderItem->fill($data);
                     $orderItem->save();
                 }
                 else {
                     // 옵션이 무조건 저장되도록 설정 (옵션없이 저장되면 옵션이 삭제되어야 함)
                     if(empty($data['option_values'])) $data['option_values'] = [];
                     $orderItem = $this->items()->create($data);
                 }
                 $savedIds[] = $orderItem->id;
             }

             // 없어진 상품은 삭제
             if(!empty($savedIds)) {
                 $this->items()->whereNotIn('id', $savedIds)->delete();
             }
         });
     }


    /**
     * @inheritDoc
     */
    public function getIsLockedAttribute()
    {
        throw new \LogicException('Not implemented'); // TODO
    }

    /**
     * @inheritDoc
     */
    public function getNameAttribute()
    {
        $item = $this->items()->first();
        // 번들상품의 경우 하위품목은 이름에 포함시키지 않음
        $count = $this->items()->where('parent_id', 0)->count();
        if($count > 1) {
            return trans('order::orders.name and count items', [
                'name' => array_get($item, 'product.name'),
                'count' => $count -1,
            ]);
        }
        return array_get($item, 'product.name');
    }

    /**
     * @inheritDoc
     */
    public function getUserNameAttribute()
    {
        return $this->user->present()->fullname;
    }

    /**
     * @inheritDoc
     */
    public function getUserPhoneAttribute()
    {
        return $this->user->phone;
    }

    /**
     * @inheritDoc
     */
    public function getUserEmailAttribute()
    {
        return $this->user->email;
    }

    /**
     * @inheritDoc
     */
    public function getPaymentGatewayAttribute()
    {
        $gatewayId = $this->getAttributeFromArray('payment_gateway_id');
        $gateway = $this->shop->paymentGateways->first(function($gateway) use ($gatewayId) {
            return $gateway->getId() == $gatewayId;
        });
        if($gateway) $gateway->setOrder($this);
        return $gateway;
    }

    /**
     * @inheritDoc
     */
    public function getPaymentMethodAttribute()
    {
        $methodId = $this->getAttributeFromArray('payment_method_id');
        return app(PaymentMethodManager::class)->find($methodId);
    }

    /**
     * @inheritDoc
     */
    public function getCountAttribute()
    {
        return $this->items()->count();
    }

    /**
     * @inheritDoc
     */
    public function getTotalPriceAttribute()
    {
        return $this->getAttributeFromArray('total_price');
    }

    /**
     * @inheritDoc
     */
    public function getTotalTaxAttribute()
    {
        return $this->getAttributeFromArray('total_tax');
    }

    /**
     * @inheritDoc
     */
    public function getTotalShippingAttribute()
    {
        return $this->getAttributeFromArray('total_shipping');
    }

    /**
     * @inheritDoc
     */
    public function getTotalDiscountAttribute()
    {
        return $this->getAttributeFromArray('total_discount');
    }

    /**
     * @inheritDoc
     */
    public function getTotalAttribute()
    {
        return $this->getAttributeFromArray('total');
    }

    /**
     * @inheritDoc
     */
    public function getSmallThumbAttribute()
    {
        return $this->items->first()->product->small_thumb;
    }

    /**
     * @inheritDoc
     */
    public function getStatusAttribute()
    {
        return $this->getRelationValue('status');
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->status ? $this->status->code : '';
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode($code)
    {
        if($status = OrderStatus::where('code', $code)->first()) {
            $this->attributes['status_id'] = $status->id;
            $this->save();
        }
    }

    public function getShopNameAttribute()
    {
        return $this->shop->name;
    }

    public function getCurrencyAttribute()
    {
        return currency($this->currency_code)->toArray()[$this->currency_code];
    }

    /**
     * @inheritDoc
     */
    public function placeTransaction($userId, $gatewayId, $paymentMethodId, $transactionId, $currencyCode, $amount, $message = '', $bankName = '', $bankAccount = '', $additionalData = [])
    {
        return $this->transactions()->create([
            'user_id' => $userId,
            'payment_method_id' => $paymentMethodId,
            'gateway_id' => $gatewayId,
            'gateway_transaction_id' => $transactionId,
            'currency_code' => $currencyCode,
            'amount' => $amount,
            'message' => $message,
            'bank_name' => $bankName,
            'bank_account' => $bankAccount,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function placeTransportation($userId, $gatewayId, $shippingMethodId, $transportationId, $currencyCode, $fee, $message = '')
    {
        return $this->transportations()->create([
            'user_id' => $userId,
            'gateway_id' => $gatewayId,
            'shipping_method_id' => $shippingMethodId,
            'gateway_transportation_id' => $transportationId,
            'currency_code' => $currencyCode,
            'fee' => $fee,
            'message' => $message,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function importItem(ShopItemInterface $item)
    {
        $data = $item->toOrderItemArray();
        $data['status_id'] = OrderStatus::PENDING;
        $orderItem = $this->items()->create($data);
        // 하위 상품이 있다면
        if(!empty($item->children)) {
            foreach ($item->children as $child) {
                // 주문아이템 부모id 세팅
                $child->parent_id = $orderItem->id;
                $childData = $child->toOrderItemArray($orderItem);
                $childData['status_id'] = OrderStatus::PENDING;
                $this->items()->create($childData);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function scopeByUser($userId, $statusId = 0)
    {
        $query = static::where('user_id', $userId);
        if($statusId) {
            $query = $query->where('status_id', $statusId);
        }
        return $query;
    }

    /**
     * @inheritDoc
     */
    public function isPayable()
    {
        return OrderStatus::isPayable($this->status_id);
    }

    /**
     * @inheritDoc
     */
    public function isCancellable()
    {
        return OrderStatus::isCancellable($this->status_id);
    }

}
