<?php

namespace Modules\Order\Repositories\Cache;

use Modules\Order\Repositories\OrderStatusRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheOrderStatusDecorator extends BaseCacheDecorator implements OrderStatusRepository
{
    public function __construct(OrderStatusRepository $orderstatus)
    {
        parent::__construct();
        $this->entityName = 'order.orderstatuses';
        $this->repository = $orderstatus;
    }
}
