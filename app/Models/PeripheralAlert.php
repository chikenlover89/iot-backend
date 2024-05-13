<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeripheralAlert extends Model
{
    use HasFactory;

    const DIRECTION_ASCENDING = 'asc';
    const DIRECTION_DESCENDING = 'desc';

    const ALLOWED_DIRECTIONS = [
        self::DIRECTION_ASCENDING,
        self::DIRECTION_DESCENDING
    ];

    protected $fillable = [
        'peripheral_id',
        'name',
        'description',
        'alert_value',
        'resolve_value',
        'direction',
    ];
}
