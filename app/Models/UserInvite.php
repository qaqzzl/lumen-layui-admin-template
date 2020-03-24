<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvite extends Model
{
    protected $table = 'user_invite';

    public $timestamps = false;

    protected $guarded = [];


}
