<?php

namespace App\Http\Requests;

use App\Models\Peripheral;
use App\Models\PeripheralAlert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdatePeripheralAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'peripheral_id' => 'sometimes|integer|min:1',
            'name'          => 'sometimes|string|max:100',
            'description'   => 'sometimes|string|max:255',
            'alert_value'   => 'sometimes|numeric',
            'resolve_value' => 'sometimes|numeric',
            'direction'     => 'sometimes|string|in:' . implode(',', PeripheralAlert::ALLOWED_DIRECTIONS),
        ];
    }

     /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (!$this->isUserAccountDevicePeripheral()) {
                abort_if(!$this->isUserAccountDevicePeripheral(), 404, 'Peripheral not found.');
            }
        });
    }

    /**
     * Determine if the given user is not a member of the account.
     *
     * @return bool
     */
    protected function isUserAccountDevicePeripheral(): bool
    {
        $peripheralId = $this->input('peripheral_id');

        return Peripheral::whereHas('device', function ($query) {
            $query->where('account_id', Auth::user()->account->id);
        })->where('id', $peripheralId)->exists();
    }
}
