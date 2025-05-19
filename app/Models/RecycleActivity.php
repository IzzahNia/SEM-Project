<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecycleActivity extends Model
{
    protected $fillable = [
        'user_id',
        'recycle_datetime',
        'recycle_weight',
        'recycle_status',
        'recycle_total_price',
        'recycle_category',
        'recycle_comment',
        'recycle_rate',
        'reward_point_earned',
        'recycle_price',
        'recycle_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}