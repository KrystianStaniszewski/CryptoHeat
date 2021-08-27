<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overcloking_params extends Model
{
    protected $fillable = [
        'Core_clock', 'Memory_clock', 'Fan', 'Pw_limit', 'Delay', 'CoreVoltage', 'MemoryController_voltage', 'MemoryVoltage', 'Gpu_id',
    ];

    protected $table = 'Overcloking_params';
}
