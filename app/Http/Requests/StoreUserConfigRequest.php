<?php

namespace App\Http\Requests;

use App\Models\UserConfig;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();

        return isset($user) && !is_null($user->active_account);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key'   => 'required|string|max:100|in:' . implode(',', UserConfig::AVAILABLE_CONFIGS),
            'value' => 'required|integer',
        ];
    }
}
