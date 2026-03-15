<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelType extends Model
{
    use CrudTrait;

    public $fillable = ['name', 'eco_class'];

    public function Motors(): HasMany
    {
        return $this->hasMany(Motor::class);
    }
}
