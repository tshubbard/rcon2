<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'servers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'name',
        'host',
        'port',
        'password',
        'disabled',
        'timezone'
    ];

    /**
     * Returns the Server Events assigned to the Server
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serverEvents()
    {
        return $this->hasMany(ServerEvent::class);
    }

    /**
     * Returns the Stream assigned to the Server
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stream()
    {
        return $this->hasMany(Stream::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
