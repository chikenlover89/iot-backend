<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use HasFactory, SoftDeletes;

    public const ALERT_TYPE_SCHEDULED       = 'scheduled';
    public const ALERT_TYPE_SENSOR_VALUE    = 'sensor_value';

    protected $fillable = [
        'resolved',
    ];

    protected $casts = [
        'resolved' => 'boolean',
    ];
}