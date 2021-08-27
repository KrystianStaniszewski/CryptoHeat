<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hardware extends Model
{
    protected $fillable = [
        'Gpu_id',
    ];

    protected $table = 'hardware';
}
