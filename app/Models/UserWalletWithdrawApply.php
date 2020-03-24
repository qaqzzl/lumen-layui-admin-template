<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletWithdrawApply extends Model
{
    protected $table = 'user_wallet_withdraw_apply';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $dateFormat = 'U';

    protected $guarded = [];

}
