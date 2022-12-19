<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogReward extends Model
{
    use HasFactory;
    protected $table = 'reward_log';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function custommer()
    {
        return $this->belongsTo(User::class, 'user_buy_id');
    }
    
    public function reward_lv1()
    {
        return $this->belongsTo(User::class, 'user_reward_level_1');
    }
    
    public function reward_lv2()
    {
        return $this->belongsTo(User::class, 'user_reward_level_2');
    }
}
