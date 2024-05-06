<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class StoreAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255|unique:accounts,name',
            'utc_offset' => ['required', 'regex:/^(\+|\-)(0[0-9]|1[0-4]):[0-5][0-9]$/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The account name is required.',
            'name.unique'   => 'An account with the same name already exists.',
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
            if ($this->userAlreadyHasAccount()) {
                $validator->errors()->add('creator_id', 'User already has created an account.');
            }
        });
    }

    /**
     * Check if the authenticated user already has an account.
     *
     * @return bool
     */
    protected function userAlreadyHasAccount(): bool
    {
        return \App\Models\Account::where('creator_id', Auth::id())->exists();
    }
}
