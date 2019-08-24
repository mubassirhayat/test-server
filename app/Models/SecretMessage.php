<?php

namespace Meveto\Models;

use Illuminate\Database\Eloquent\Model;

class SecretMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'secret'
    ];
}
