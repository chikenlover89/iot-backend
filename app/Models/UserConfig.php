<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfig extends Model
{
    use HasFactory;

    const CONFIG_EMAIL_ALERTS = 'email_alerts';

    const AVAILABLE_CONFIGS = [
        self::CONFIG_EMAIL_ALERTS
    ];

    protected $fillable = [
        'key',
        'value'
    ];
}
