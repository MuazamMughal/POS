<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Sale;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'loyalty_points',
        'credit_limit',
        'balance_due',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
