<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravelcm\Subscriptions\Traits\HasPlanSubscriptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasPlanSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

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

    public function wishlistProperty()
    {
        return $this->belongsToMany(Property::class, 'favourites');
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'agent_id');
    }

    public function isVerified()
    {
        return !is_null($this->email_verified_at);
    }
}
