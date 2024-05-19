<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Denomination extends Model
{
    use HasFactory;

    protected $table = 'denominations';
    protected $guarded = [];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function denominationLog(): HasMany
    {
        return $this->hasMany(DenominationLog::class, 'denomination_id');
    }
}
