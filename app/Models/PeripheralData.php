<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeripheralData extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $casts = [
        'value' => 'float'
    ];
}
