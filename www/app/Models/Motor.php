<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motor extends Model
{
    use CrudTrait;

    public $fillable = ['name', 'fuel_type_id', 'price'];
    public function FuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }
}
