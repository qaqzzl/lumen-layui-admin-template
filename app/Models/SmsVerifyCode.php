<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsVerifyCode extends Model
{
    protected $table = 'sms_verify_code';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

}
