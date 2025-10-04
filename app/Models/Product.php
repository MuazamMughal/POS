<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model

{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'price',
        'stock',
        'tax',
        'discount',
        'category_id',
        'brand_id',
        'unit_id',
    ];
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    public function unit() {
        return $this->belongsTo(Unit::class);
    }

}
