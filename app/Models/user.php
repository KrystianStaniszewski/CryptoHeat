<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'firstName', 'lastName', 'email', 'pseudo', 'password', 'isAdmin', 'wallet_id', 'rig_id',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $table = 'user';
}
