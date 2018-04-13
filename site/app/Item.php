<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    public function itemType()
    {
        return $this->hasOne(ItemType::class, 'id', 'item_type_id');
    }
}
