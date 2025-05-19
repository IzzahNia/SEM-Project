<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'reward_name',
        'reward_description',
        'reward_duration_date',
        'reward_status',
        'reward_quantity',
        'reward_point_required',
        'reward_image',
    ];

    public function redeemRewards()
    {
        return $this->hasMany(RedeemReward::class);
    }
}