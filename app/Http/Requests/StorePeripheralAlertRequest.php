<?php

namespace App\Http\Requests;

use App\Models\Peripheral;
use App\Models\PeripheralAlert;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StorePeripheralAlertRequest extends FormRequest
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
            'peripheral_id' => 'required|integer',
            'name'          => 'required|string|max:100',
            'description'   => 'required|string|max:255',
            'alert_value'   => 'required|numeric',
            'resolve_value' => 'required|numeric',
            'direction'     => 'required|string|in:' . implode(',', PeripheralAlert::ALLOWED_DIRECTIONS),
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
            if($this->peripheralHasAlert()) {
                abort_if($this->peripheralHasAlert(), 422, 'Peripheral alert already exists.');
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


    /**
     * Determine if peripheral alert exists.
     *
     * @return bool
     */
    protected function peripheralHasAlert(): bool
    {
        return PeripheralAlert::where('peripheral_id', $this->input('peripheral_id'))->exists();
    }
}
