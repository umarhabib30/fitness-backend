<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'package_id',
        'started_at',
        'ends_at',
        'status',
    ];

    protected $dates = ['started_at', 'ends_at'];

    protected $casts = [
        'started_at' => 'date',
        'ends_at' => 'date',
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
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }
}
?>
