<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable=[ 'name', 'duration_unit', 'duration', 'price', 'description', 'status', 'trainer_id' ];
    
    protected $casts = [
        'duration'      => 'integer',
        'price'         => 'double',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');  
    }
}
