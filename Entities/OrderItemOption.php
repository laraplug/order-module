<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Option;
use Modules\Product\Entities\Product;
use Modules\Shop\Contracts\ShopProductOptionInterface;

class OrderItemOption extends Model implements ShopProductOptionInterface
{
    protected $table = 'order__order_item_options';
    protected $fillable = [
        'product_id',
        'slug',
        'value',
    ];
    protected $appends = [
        'type',
        'sort_order',
        'required',
        'values',
        'is_collection',
        'is_system',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getProductOption()
    {
        return $this->product->options->where('slug', $this->slug)->first() ?: new Option();
    }

    public function getNameAttribute(): string
    {
        return $this->getProductOption()->name;
    }

    public function getTypeAttribute(): string
    {
        return $this->getProductOption()->type;
    }

    public function getSortOrderAttribute(): int
    {
        return $this->getProductOption()->sort_order ?: 0;
    }

    public function getRequiredAttribute()
    {
        return $this->getProductOption()->required;
    }

    public function getValuesAttribute()
    {
        return $this->getProductOption()->values;
    }

    public function getIsCollectionAttribute(): int
    {
        return $this->getProductOption()->is_collection;
    }

    public function getIsSystemAttribute(): int
    {
        return $this->getProductOption()->is_system;
    }
}
