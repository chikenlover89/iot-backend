<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const BLOCKED = 'Y';
    public const ACTIVE  = 'N';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Get all of the accounts for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, Member::class);
    }

    /**
     * Get all of the invitations for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'email', 'email')
            ->where('accepted', false);
    }

    /**
     * Get user active account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'active_account');
    }

    /**
     * Set user active account
     *
     * @param Account $account
     */
    public function setAccount(Account $account) {
        $this->active_account = $account->id;
        $this->save();
    }
}
