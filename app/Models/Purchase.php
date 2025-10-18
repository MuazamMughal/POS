<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'product_id',
        'quantity',
        'unit_price',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
