<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
     * The attributes that should never be mass assignable
     *
     * @var array
     */
    protected $guarded = [
        'role_id',
    ];

    /**
     * Returns the servers assigned to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servers()
    {
        return $this->hasMany(Server::class, 'account_id', 'account_id');
    }

    /**
     * The accounts that belong to the user.
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * Returns the User's role_id
     *
     * @return boolean
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Returns if the User is an Admin
     *
     * todo: this is probably implemented poorly and should be fixed
     *
     * @return boolean
     */
    public function isAdmin()
    {
        // todo: hardcoded "4" is probably bad
        return $this->role_id === 4;
    }

    /**
     * Returns if the User is an Admin
     *
     * todo: this is probably implemented poorly and should be fixed
     *
     * @return boolean
     */
    public function isMod()
    {
        // todo: hardcoded "3" is probably bad
        return $this->role_id === 3;
    }

    /**
     * Returns if the User can add or remove Admin status
     *
     * @return boolean
     */
    public function canChangeAdmin()
    {
        // todo: hardcoded "4" is probably bad
        return $this->role_id === 4;
    }

    /**
     * Returns if the User can add or remove Moderator status
     *
     * @return boolean
     */
    public function canChangeMod()
    {
        // todo: hardcoded "3"/"4" is probably bad
        return $this->role_id === 3 || $this->role_id === 4;
    }

    public function role()
    {
        return $this->hasOne(\App\Role::class, 'id', 'role_id');
    }
}
