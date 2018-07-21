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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'server_event_tag', 'server_event_id', 'tag_id');

    }

    /**
     * Scope a query to only include public server events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', '=', 1);
    }

    /**
     * Scope a query to only include public server events.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByVotes($query)
    {
        return $query->orderBy('votes', 'DESC');
    }
}
