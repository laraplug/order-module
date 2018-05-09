<?php

namespace Modules\Order\Entities;

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
        // Virtual Attribute
        'options',
    ];
    protected $casts = [
        'options' => 'array',
    ];
    protected $appends = [
        'options',
        'product',
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
    public function options()
    {
        return $this->hasMany(OrderItemOption::class, 'order_item_id');
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
        return $this->product()->first();
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
    public function getOptionsAttribute()
    {
        return $this->options()->get();
    }

    /**
     * Save Options
     * @param array $options
     */
    public function setOptionsAttribute(array $options = [])
    {
        static::saved(function ($model) use ($options) {
            $savedOptionIds = [];
            foreach ($options as $data) {
                if (empty(array_filter($data)) || empty($data['slug'])) {
                    continue;
                }
                // Create option or enable it if exists
                $productOption = $this->product->options->where('slug', $data['slug'])->first();
                if($productOption) {
                    $option = $this->options()->updateOrCreate([
                        'product_id' => $this->product_id,
                        'slug' => $data['slug'],
                    ], $data);
                    $savedOptionIds[] = $option->id;
                }
            }
            $this->options()->whereNotIn('id', $savedOptionIds)->delete();
        });
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
}
