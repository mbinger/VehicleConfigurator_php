<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use CrudTrait;
    //

    public $fillable = [
        'name'
    ];

    public function Cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
