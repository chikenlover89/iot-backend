<?php

namespace App\Http\Requests;

use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:30',
            'description' => 'sometimes|string|max:255',
            'type' => [
                'sometimes',
                'string',
                'max:30',
                Rule::in([
                    Device::MULTI_PURPOSE_DEVICE,
                    Device::SENSOR_DEVICE,
                    Device::SWITCH_DEVICE,
                ]),
            ],
            'network' => [
                'sometimes',
                'string',
                'max:30',
                Rule::in([
                    Device::NETWORK_WIFI,
                ]),
            ],
        ];
    }
}
