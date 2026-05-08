<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'interval',
        'duration_days',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price'    => 'decimal:2',
        'is_active'=> 'boolean',
    ];
}
?>
