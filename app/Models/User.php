<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as IAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements IAuthenticatable
{
    use Authenticatable, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email', 'password'
    ];
}
