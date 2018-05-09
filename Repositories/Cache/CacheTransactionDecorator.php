<?php

namespace Modules\Order\Repositories\Cache;

use Modules\Order\Repositories\TransactionRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheTransactionDecorator extends BaseCacheDecorator implements TransactionRepository
{
    public function __construct(TransactionRepository $transaction)
    {
        parent::__construct();
        $this->entityName = 'order.transactions';
        $this->repository = $transaction;
    }
}
