<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderOption extends Model
{
    public $fillable = [
        'order_id',
        'option_id'
    ];

    public function Option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
