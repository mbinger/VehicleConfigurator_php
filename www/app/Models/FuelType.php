<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelType extends Model
{
    public function Motors(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
