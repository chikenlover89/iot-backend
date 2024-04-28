<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peripheral extends Model
{
    use HasFactory;

    public const FUNCTION_INPUT  = 'input';
    public const FUNCTION_OUTPUT = 'output';
    public const FUNCTION_UNKNOWN = 'unknown';

    public const TYPE_TEMPERATURE = 'temperature';
    public const TYPE_HUMIDITY    = 'humidity';
    public const TYPE_CO2         = 'co2';
    public const TYPE_RELAY       = 'relay';
    public const TYPE_SWITCH      = 'switch';
    public const TYPE_UNKNOWN     = 'unknown';

    public const PREFIX_TEMPERATURE = 't';
    public const PREFIX_HUMIDITY    = 'h';
    public const PREFIX_CO2         = 'co2';
    public const PREFIX_RELAY       = 'r';
    public const PREFIX_SWITCH      = 'sw';

    public const OUTPUT_PERIPHERALS = [
        self::PREFIX_RELAY => self::TYPE_RELAY,
    ];

    public const INPUT_PERIPHERALS = [
        self::PREFIX_TEMPERATURE => self::TYPE_TEMPERATURE,
        self::PREFIX_HUMIDITY    => self::TYPE_HUMIDITY,
        self::PREFIX_CO2         => self::TYPE_CO2,
        self::PREFIX_SWITCH      => self::TYPE_SWITCH,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hidden',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(PeripheralData::class);
    }

    public static function findOrCreate(int $deviceId, string $parameter_id): self
    {
        $peripheral = self::where('device_id', $deviceId)
            ->where('parameter_id', $parameter_id)
            ->first();

        if (null === $peripheral) {
            $prefix   = explode('_', $parameter_id)[0];
            $type     = self::TYPE_UNKNOWN;
            $function = self::FUNCTION_UNKNOWN;

            if (in_array($prefix, array_keys(self::INPUT_PERIPHERALS), true)) {
                $type     = self::INPUT_PERIPHERALS[$prefix];
                $function = self::FUNCTION_INPUT;
            }
            if (in_array($prefix, array_keys(self::OUTPUT_PERIPHERALS), true)) {
                $type     = self::OUTPUT_PERIPHERALS[$prefix];
                $function = self::FUNCTION_OUTPUT;
            }

            $peripheral               = new self();
            $peripheral->device_id    = $deviceId;
            $peripheral->parameter_id = $parameter_id;
            $peripheral->type         = $type;
            $peripheral->function     = $function;
            $peripheral->save();
        }

        return $peripheral;
    }

    public static function isValidParameterId(string $parameter_id): bool
    {
        $divided_parameter = explode('_', $parameter_id);

        return count($divided_parameter) == 2 && is_numeric($divided_parameter[1]);
    }
}
