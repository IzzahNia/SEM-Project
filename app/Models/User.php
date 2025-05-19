<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_number',
        'last_active_at',
        'current_reward_points', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Last Active Date
    public function scopeActivePast7Days($query)
    {
        return $query->where('last_active_at', '>=', Carbon::now()->subDays(7));
    }
    
    // User reward points
    public function rewardPoints()
    {
        return $this->hasMany(RewardPoint::class);
    }

    public function incrementTotalRewardPoints($value)
    {
        $this->current_reward_points += $value;
        $this->save();
    }

    // Redeemed rewards relationship
    public function redeemedRewards()
    {
        return $this->hasMany(RedeemReward::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
}
