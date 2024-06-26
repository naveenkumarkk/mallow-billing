<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = [];

    public function scopeActive(Builder $query) :Builder
    {
        return $query->where('status',true);
    }

    public function purchaseLog(): HasMany
    {
        return $this->hasMany(PurchaseLog::class, 'product_id');
    }
}
