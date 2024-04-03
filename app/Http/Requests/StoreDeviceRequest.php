<?php

namespace App\Http\Requests;

use App\Models\Device;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'type' => [
                'required',
                'string',
                'max:30',
                Rule::in([
                    Device::MULTI_PURPOSE_DEVICE,
                    Device::SENSOR_DEVICE,
                    Device::SWITCH_DEVICE,
                ]),
            ],
            'network' => [
                'required',
                'string',
                'max:30',
                Rule::in([
                    Device::NETWORK_WIFI,
                ]),
            ],
        ];
    }
}
