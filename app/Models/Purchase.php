<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\Product;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice_no',
        'purchase_date',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
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
