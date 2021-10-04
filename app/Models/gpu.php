<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gpu extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'Name', 'Brand', 'Ram', 'Ram_Brand',
    ];

    protected $table = 'gpu';
}
