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
              // 졸업앨범으로 들어올 시 optionValues 에 year 란 추가(Ho)
              if($item->product->type =="graduatebook"||$item->product->type =="diplomabook"){
                $item_data = $item->option_values;
                $graduate_year = date("Y",strtotime("+1 year"));
                $item_data->put("year",$graduate_year);
                $item->option_values = $item_data;
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
