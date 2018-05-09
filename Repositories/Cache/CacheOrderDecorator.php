<?php

namespace Modules\Order\Repositories\Cache;

use Modules\Order\Repositories\OrderRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderDecorator extends BaseCacheDecorator implements OrderRepository
{
    public function __construct(OrderRepository $order)
    {
        parent::__construct();
        $this->entityName = 'order.orders';
        $this->repository = $order;
    }
}
