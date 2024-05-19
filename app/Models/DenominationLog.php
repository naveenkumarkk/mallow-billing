<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DenominationLog extends Model
{
    use HasFactory;

    protected $table = 'denomination_logs';
    protected $guarded = [];

    public function denomination(): BelongsTo
    {
        return $this->belongsTo(Denomination::class, 'denomination_id','id');
    }
}
