<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPoint extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RewardPoint.php
    protected static function booted()
    {
        static::updated(function (RewardPoint $rewardPoint) {
            if ($rewardPoint->recycle_status === 'Completed') {
                $rewardPoint->user->incrementTotalRewardPoints($rewardPoint->reward_point_earned);
            }
        });
    }
}