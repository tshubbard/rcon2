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
        'created_by_user_id',
        'event_type',
        'commands',
        'command_trigger',
        'command_timer',
        'is_indefinite',
        'is_public',
        'is_active',
        'start_date',
        'end_date',
    ];
}
