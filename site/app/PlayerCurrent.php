<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerCurrent extends Model
{
    protected $table = 'players_current';

    public function player()
    {
        return $this->belongsTo(Player::class, 'steam_id', 'steam_id');
    }
}
