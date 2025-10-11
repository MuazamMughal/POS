<?php

namespace App\Models;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
       'invoice_no',
       'customer_id',
       'subtotal',
       'discount_type',
       'discount_value',
       'discount_reason',
       'tax_amount',
       'total',
       'payment_method',
       'amount_paid',
       'change_returned',
       'notes',
       'sold_by',
    ];
    public function items() {
        return $this->hasMany(SaleItem::class);
    }
}
