<?php

namespace Modules\Order\Repositories\Eloquent;

use Modules\Order\Repositories\TransactionRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentTransactionRepository extends EloquentBaseRepository implements TransactionRepository
{

    /**
     * @inheritDoc
     */
    public function create($data)
    {
        $model = $this->model->newInstance($data);
        $model->order_id = $data['order_id'];
        $model->save();
        return $model;
    }

}
