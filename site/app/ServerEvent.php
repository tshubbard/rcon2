<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerEvent extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'server_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'server_id',
        'event_type',
        'command_key',
        'command',
        'command_trigger',
        'command_text',
        'command_itemid',
        'command_target',
        'command_quantity',
        'interval',
        'is_indefinite',
        'is_public',
        'is_active',
        'start_date',
        'end_date',
    ];
}
