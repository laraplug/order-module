<?php

namespace Modules\Order\Events;

use Modules\Order\Entities\Order;

class OrderWasDeleted
{
    /**
     * @var Order
     */
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

}
