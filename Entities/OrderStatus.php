<?php

namespace Modules\Order\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use Translatable;

    const PENDING_PAYMENT = 1;
    const PENDING_PAYMENT_APPROVAL = 2;
    const PENDING = 3;
    const PROCESSING = 4;
    const PROCESSED = 5;
    const SHIPPING = 6;
    const SHIPPED = 7;
    const COMPLETED = 9;
    const CANCELED = 10;
    const PARTIALLY_CANCELED = 11;
    const REFUNDED = 12;
    const PARTIALLY_REFUNDED = 13;

    protected $table = 'order__order_statuses';
    public $translatedAttributes = [
        'name',
        'description',
    ];
    protected $fillable = [
        'code'
    ];

    public static function getIdByCode($code)
    {
        $model = static::where('code', $code)->first();
        return $model ? $model->getKey() : 0;
    }

    public static function isPayable($statusId)
    {
        return $statusId == self::PENDING_PAYMENT;
    }

    public static function isCancellable($statusId)
    {
        $statuses = [
            self::PENDING,
        ];
        return in_array($statusId, $statuses);
    }
}
