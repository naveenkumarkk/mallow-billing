<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseLog extends Model
{
    use HasFactory;
    protected $table = 'purchase_logs';
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customerPurchaseInfo()
    {
        return $this->belongsTo(CustomerPurchaseInfo::class, 'sales_id');
    }


}
