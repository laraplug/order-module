<?php

namespace Modules\Order\Entities;

use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;
use Modules\Shop\Contracts\ShopItemInterface;
use Modules\Shop\Repositories\ShippingMethodManager;

/**
 * OrderItem
 */
class OrderItem extends Model implements ShopItemInterface
{
    protected $table = 'order__order_items';
    protected $fillable = [
        'shop_id',
        'product_id',
        'parent_id',
        'price',
        'quantity',
        'tax',
        'discount',
        'total',
        'note',
        'shipping_method_id',
        'shipping_storage_id',
        'status_id',
        'option_values',
    ];
    protected $casts = [
        'option_values' => 'collection',
    ];
    protected $appends = [
        'product',
        'status_name',
    ];
    protected $hidden = [
        'order',
    ];

    /**
     * @inheritDoc
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @inheritDoc
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @inheritDoc
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @inheritDoc
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @inheritDoc
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    /**
     * @inheritDoc
     */
    public function getProductAttribute()
    {
        return $this->getRelationValue('product');
    }

    /**
     * @inheritDoc
     */
    public function getPriceAttribute()
    {
        return $this->getAttributeFromArray('price');
    }

    /**
     * @inheritDoc
     */
    public function getQuantityAttribute()
    {
        return $this->getAttributeFromArray('quantity');
    }

    /**
     * @inheritDoc
     */
    public function getTotalAttribute()
    {
        return $this->getAttributeFromArray('total');
    }

    /**
     * @inheritDoc
     */
    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }

    /**
     * @inheritDoc
     */
    public function getShippingMethodAttribute()
    {
        return $this->shipping_method_id ? app(ShippingMethodManager::class)->find($this->shipping_method_id) : null;
    }

    /**
     * @inheritDoc
     */
    public function getStatusNameAttribute()
    {
        return $this->status ? $this->status->name : '';
    }

    /**
     * @inheritDoc
     */
    public function setOptionValuesAttribute($optionValues)
    {
        if(!$optionValues instanceof Collection) {
            $optionValues = collect($optionValues);
        }
        $this->attributes['option_values'] = $optionValues->toJson(JSON_NUMERIC_CHECK);
    }

    /**
     * 주문후 보이는 Action뷰
     */
    public function getProductActionFields()
    {
        return $this->product->getEntityActionFields($this);
    }

    /**
     * @inheritDoc
     */
    public function toOrderItemArray(ShopItemInterface $parentItem = null)
    {   
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        // Dynamic Relation Injection
        #i: Convert array to dot notation
        $config = implode('.', ['asgard.order.config.orderitem.relations', $method]);

        #i: Relation method resolver
        if (config()->has($config)) {
            $function = config()->get($config);
            $bound = $function->bindTo($this);

            return $bound();
        }

        #i: No relation found, return the call to parent (Eloquent) to handle it.
        return parent::__call($method, $parameters);
    }
}
