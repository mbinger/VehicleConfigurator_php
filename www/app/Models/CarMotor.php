<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarMotor extends Model
{
    use CrudTrait;

    public $fillable = ['car_id', 'motor_id'];

    public function Car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function Motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }
}
