<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserConfigRequest;
use App\Http\Requests\UserConfigRequest;
use App\Http\Resources\UserConfigCollection;
use App\Http\Resources\UserConfigResource;
use App\Models\UserConfig;
use Auth;

class UserConfigController extends Controller
{
    public function index(UserConfigRequest $request)
    {
        return new UserConfigCollection(Auth::user()->config);
    }

    public function store(StoreUserConfigRequest $request)
    {
        $validated = $request->validated();
        $config = Auth::user()->config()->where('key', $validated['key'])->first();

        if (!$config->exists()) {
            $config             = new UserConfig($request->validated());
            $config->user_id    = Auth::id();
            $config->account_id = Auth::user()->account->id;
            $config->save();
        } else {
            $config->update($request->validated());
        }

        return new UserConfigResource($config);
    }
}
