<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use CrudTrait;

    public $fillable = ['vendor_id', 'name', 'price'];

    public function Vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
