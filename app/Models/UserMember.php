<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMember extends Model
{
    protected $table = 'user_member';

    protected $primaryKey = 'member_id';

    protected $dateFormat = 'U';

    protected $guarded = [];


    public function getAvatarAttribute($value) {
        if(!filter_var($value, FILTER_VALIDATE_URL)){
            if (!empty($value)) return config('config.QiniuDomain').$value;
        }
        if (!$value) {
            $value = 'https://cdn.qaqzz.com/admin.png';
        }
        return $value;

    }

    public function user_auths() {
        return $this->hasMany(UserAuths::class, 'member_id','member_id');
    }

    //钱包
    public function user_wallet() {
        return $this->hasOne(UserWallet::class,'member_id','member_id');
    }
}

