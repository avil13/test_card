<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['user_id', 'title', 'balance'];
    protected $hidden = ['user_id', 'updated_at', 'created_at'];
}
