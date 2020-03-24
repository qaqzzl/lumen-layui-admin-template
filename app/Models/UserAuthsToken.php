<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuthsToken extends Model
{
    protected $table = 'user_auths_token';

    public $timestamps = false;

    protected $guarded = [];
}

