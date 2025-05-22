<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'favorite_products'
    ];

    protected $casts = [
        'favorite_products' => 'array'
    ];
}
