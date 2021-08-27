<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workers extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'connexionKey', 'name','User_id', 'Hardware_id',
    ];

    protected $table = 'machine';
}
