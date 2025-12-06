<?php

namespace Denizgolbas\EloquentSaveTogether\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];
}
