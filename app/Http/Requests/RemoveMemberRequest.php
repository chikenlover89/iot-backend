<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class RemoveMemberRequest extends FormRequest
{
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->userNotInAccount()) {
                $validator->errors()->add('member', 'Member is not registered in this account.');
            }
        });
    }

    /**
     * Determine if the given user is not a member of the account.
     *
     * @return bool
     */
    protected function userNotInAccount(): bool
    {
        $accountId = $this->route('account')->id;
        $userId    = $this->route('user')->id;

        return !\App\Models\Account::find($accountId)->members()->where('users.id', $userId)->exists();
    }
}
