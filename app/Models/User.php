<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, \Sushi\Sushi;

    protected $rows = [
        [
            'name' => 'PE Vermeulen',
        ],
    ];
}
