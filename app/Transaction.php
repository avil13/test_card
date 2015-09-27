<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function card()
    {
        return $this->belongsTo('App\Card', 'card_id');
    }
}
