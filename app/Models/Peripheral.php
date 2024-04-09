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

    public const TYPE_TEMPERATURE = 'temperature';
    public const TYPE_HUMIDITY    = 'humidity';
    public const TYPE_CO2         = 'co2';
    public const TYPE_RELAY       = 'relay';

    public const PREFIX_TEMPERATURE = 't_';
    public const PREFIX_HUMIDITY    = 'h_';
    public const PREFIX_CO2         = 'co2_';
    public const PREFIX_RELAY       = 'r_';

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
}
