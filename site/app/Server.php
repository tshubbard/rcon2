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
}
