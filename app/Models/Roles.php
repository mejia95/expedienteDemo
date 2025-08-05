<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Roles extends Model
{
    //
    protected $connection = "mongodb";
    protected $table = "CatRoles";
    protected $primaryKey = '_id';
    protected $keyType = 'string';
}
