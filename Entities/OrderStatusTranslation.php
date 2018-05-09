<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderStatusTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
    ];
    protected $table = 'order__order_status_translations';
}
