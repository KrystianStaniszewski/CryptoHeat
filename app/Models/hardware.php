<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hardware extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'Gpu_id', 'Machine_id'
    ];

    protected $table = 'hardware';
}
