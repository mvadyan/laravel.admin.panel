<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->roles()->where('name', 'user')->exists();
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->roles()->where('name', 'disabled')->exists();
    }

    /**
     * @return bool
     */
    public function isVisitor(): bool
    {
        return $this->roles()->where('name', '')->exists();
    }
}
