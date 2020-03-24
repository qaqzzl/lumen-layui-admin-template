<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $table = 'user_wallet';

    protected $primaryKey = 'member_id';

    public $timestamps = false;

    protected $guarded = [];

}
