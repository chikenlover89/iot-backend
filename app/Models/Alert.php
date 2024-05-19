<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use HasFactory, SoftDeletes;

    public const ALERT_TYPE_SCHEDULED    = 'scheduled';
    public const ALERT_TYPE_SENSOR_VALUE = 'sensor_value';

    protected $fillable = [
        'resolved',
    ];

    protected $casts = [
        'resolved' => 'boolean',
    ];

    public static function processPeripheralAlert(float $value, PeripheralAlert $alertConfig): void
    {
        $alert_comparison   = $alertConfig->direction === PeripheralAlert::DIRECTION_ASCENDING ? '>' : '<';
        $resolve_comparison = $alertConfig->direction === PeripheralAlert::DIRECTION_ASCENDING ? '<' : '>';

        $unresolved_alert = self::where('device_id', $alertConfig->device_id)
            ->where('resolved', false)
            ->where('name', $alertConfig->name)
            ->first();

        if (
            $unresolved_alert !== null
            && Helper::compare($value, $alertConfig->resolve_value, $resolve_comparison)
        ) {
            $unresolved_alert->resolved = true;
            $unresolved_alert->save();

            return;
        }

        if(Helper::compare($value, $alertConfig->resolve_value, $alert_comparison)) {
            $alert              = new Alert();
            $alert->account_id  = $alertConfig->account_id;
            $alert->device_id   = $alertConfig->device_id;
            $alert->name        = $alertConfig->name;
            $alert->description = $alertConfig->description;;
            $alert->type        = Alert::ALERT_TYPE_SENSOR_VALUE;
            $alert->save();
        }
    }
}