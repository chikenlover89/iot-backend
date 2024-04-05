<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    public const MULTI_PURPOSE_DEVICE = 'multi_purpose';
    public const SENSOR_DEVICE        = 'sensor';
    public const SWITCH_DEVICE        = 'switch';

    public const NETWORK_WIFI = 'wifi';

    protected $fillable = [
        'name',
        'description',
        'type',
        'network',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class);
    }
}
