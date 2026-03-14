<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    public function Vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
