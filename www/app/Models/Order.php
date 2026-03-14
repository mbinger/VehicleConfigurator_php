<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
