<?php

namespace Modules\Order\Repositories\Eloquent;

use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasDeleted;
use Modules\Order\Events\OrderWasUpdated;

use Modules\Order\Repositories\OrderRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentOrderRepository extends EloquentBaseRepository implements OrderRepository
{

    /**
     * @inheritDoc
     */
    public function create($data)
    {
        $items = $data['items'];

        unset($data['items']);
        $model = $this->model->newInstance($data);
        $model->shop_id = $data['shop_id'];
        $model->user_id = $data['user_id'];
        $model->save();
        // Save Items
        if(!empty($items)) {
            foreach ($items as $item) {
                //        items 에도 부가가치세 정보를 넣어주기 위해 foreach로 돌린다 20200904 Ho
                if($item->product['is_tax_free']){
                    $item->tax_free_amount = ($item->total);
                }else{
                    $totalprice = $item->price;
                    $supply_amount = floor(($totalprice)/1.1);
                    $tax_amount = $totalprice - $supply_amount;
                    $item->supply_amount = $supply_amount;
                    $item->tax_amount = $tax_amount;
                }

                $model->importItem($item);
            }
        }

        event(new OrderWasCreated($model, $data));
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function update($model, $data)
    {
        $model = parent::update($model, $data);
        event(new OrderWasUpdated($model, $data));
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function destroy($model)
    {
        event(new OrderWasDeleted($model));
        return parent::destroy($model);
    }

}
