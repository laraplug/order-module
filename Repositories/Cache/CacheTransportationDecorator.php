<?php

namespace Modules\Order\Repositories\Cache;

use Modules\Order\Repositories\TransportationRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheTransportationDecorator extends BaseCacheDecorator implements TransportationRepository
{
    public function __construct(TransportationRepository $transportation)
    {
        parent::__construct();
        $this->entityName = 'order.transportations';
        $this->repository = $transportation;
    }
}
