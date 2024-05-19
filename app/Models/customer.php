<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer_mallow';
    protected $guarded = [];

    public function purchaseInfo()
    {
        return $this->hasMany(CustomerPurchaseInfo::class);
    }
}
