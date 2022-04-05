<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Price extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];
}
