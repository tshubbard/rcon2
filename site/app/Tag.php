<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function serverEvents()
    {
        return $this->belongsToMany(ServerEvent::class, 'server_event_tag', 'tag_id', 'server_event_id');

    }
}
