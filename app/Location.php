<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = true;
    protected $fillable = ['user_location'];
}
