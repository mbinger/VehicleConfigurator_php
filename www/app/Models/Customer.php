<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $fillable = [
        'last_name',
        'first_name',
        'birthday'
    ];
}
