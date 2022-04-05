<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Station extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];
}
