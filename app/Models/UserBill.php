<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBill extends Model
{
    protected $table = 'user_bill';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

}
