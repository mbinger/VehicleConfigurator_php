<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use CrudTrait;

    public $fillable = ['name', 'price'];
}
