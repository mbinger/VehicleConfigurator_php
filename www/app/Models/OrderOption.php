<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    public $fillable = [
        'order_id',
        'option_id'
    ];
}
