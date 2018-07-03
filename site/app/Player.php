<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at',
        'last_login',
        'last_steam_sync',
    ];

    protected $table = 'players';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    public function current()
    {
        return $this->hasMany(PlayerCurrent::class, 'steam_id', 'steam_id');
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
