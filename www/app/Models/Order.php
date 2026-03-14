<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public $fillable = [
        'number',
        'price',
        'customer_id',
        'car_id',
        'motor_id',
        'color',
        'status'
    ];

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function Car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function Motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    public function OrderOptions(): HasMany
    {
        return $this->hasMany(OrderOption::class);
    }



}
