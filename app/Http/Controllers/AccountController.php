<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return new AccountCollection(Auth::user()->memberships);
    }

    public function show(Account $account)
    {
        $this->authorize('show', $account);

        return new AccountResource($account);
    }

    public function store(StoreAccountRequest $request)
    {
        $account = new Account($request->validated());

        $account->creator_id = Auth::id();
        $account->save();

        return new AccountResource($account);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return new AccountResource($account);
    }

    public function destroy(Account $account)
    {
        $this->authorize('destroy', $account);

        $account->delete();

        return response()->json(['message' => 'Account deleted successfully.']);
    }
}
