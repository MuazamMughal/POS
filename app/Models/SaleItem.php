<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
      'sale_id',
      'product_id',
      'quantity',
      'unit_price',
      'total_price',
    ];
    public function product() {
        return $this->belongsTo(Product::class , 'product_id', 'id');
    }
    public function sale() {
        return $this->belongsTo(Sale::class , 'sale_id', 'id');
    }
}
