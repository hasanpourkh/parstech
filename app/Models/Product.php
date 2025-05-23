<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'type',
        'name',
        'code',
        'category_id',
        'brand_id',
        'image',
        'gallery',
        'video',
        'short_desc',
        'description',
        'stock',
        'min_stock',
        'unit',
        'barcode',
        'is_active',
        'buy_price',    // ← اضافه کن
        'sell_price',   // ← اضافه کن
        'discount',     // ← اگر داشتی
        'store_barcode' // ← اگر داشتی
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'stock' => 'integer',
        'min_stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function shareholders()
    {
        return $this->belongsToMany(\App\Models\Person::class, 'product_shareholder', 'product_id', 'person_id')
            ->withPivot('percent');
    }
}
