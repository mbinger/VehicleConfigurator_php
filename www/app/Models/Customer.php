<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    public $fillable = [
        'last_name',
        'first_name',
        'birthday',
        'number'
    ];

    public function Orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
