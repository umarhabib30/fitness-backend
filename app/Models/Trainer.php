<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Trainer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Legacy link to a user record kept for data migration compatibility.
     */
    public function legacyUser()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Clients (regular users) owned by this trainer.
     */
    public function clients()
    {
        return $this->hasMany(User::class, 'trainer_id');
    }

    /**
     * Current active subscription (if any).
     */
    public function activeSubscription()
    {
        return $this->hasOne(TrainerSubscription::class)
                    ->where('status', 'active')
                    ->whereDate('ends_at', '>=', now());
    }

    public function subscriptions()
    {
        return $this->hasMany(TrainerSubscription::class);
    }

    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $value ? str_replace('+', '', $value) : null;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->attributes['phone_number'] ? '+' . $this->attributes['phone_number'] : null;
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }
}
