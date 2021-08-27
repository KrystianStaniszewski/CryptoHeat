<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rig extends Model
{
    protected $fillable = [
        'Name', 'Hardware', 'Hardware_id',
    ];

    protected $table = 'rig';
}
