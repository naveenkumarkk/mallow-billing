<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPurchaseInfo extends Model
{
    use HasFactory;

    protected $table = 'customer_purchase_info';
    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }

    public function purchaseHistory()
    {
        return $this->hasMany(PurchaseLog::class, 'sales_id');
    }
}
