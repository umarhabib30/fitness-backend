<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TrainerSubscription extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'trainer_id',
        'package_id',
        'started_at',
        'ends_at',
        'status',
        'reviewed_at',
        'transaction_reference', // new field for manual payment reference
        'rejection_reason', // optional reason when admin rejects
    ];

    protected $dates = ['started_at', 'ends_at', 'reviewed_at'];

    protected $casts = [
        'started_at' => 'date',
        'ends_at' => 'date',
        'rejection_reason' => 'string',
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function package()
    {
        return $this->belongsTo(TrainerPackage::class);
    }

    /**
     * Attribute to quickly check if subscription is active.
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active'
            && ($this->ends_at === null || $this->ends_at->isToday() || $this->ends_at->isFuture());
    }
}
