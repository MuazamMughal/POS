<?php

namespace App\Models;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_no',
        'total',
        'tax',
        'discount',
        'grand_total',
        'payment_method',
    ];
    public function items() {
        return $this->hasMany(SaleItem::class);
    }
}
