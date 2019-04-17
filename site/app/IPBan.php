<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPBan extends Model
{
    protected $table = 'server_ipbans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'server_id',
        'created_by_user_id',
        'name',
        'ipaddress'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
