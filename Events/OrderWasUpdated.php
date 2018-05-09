<?php

namespace Modules\Order\Events;

use Modules\Order\Entities\Order;

class OrderWasUpdated
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var Order
     */
    public $order;

    public function __construct(Order $order, array $data)
    {
        $this->data = $data;
        $this->order = $order;
    }

}
