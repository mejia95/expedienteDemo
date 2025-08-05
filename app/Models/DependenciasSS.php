<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DependenciasSS extends Model
{

    protected $connection = "mongodb";
    protected $table = "DependenciasSS";
    protected $primaryKey = '_id';
    protected $keyType = 'string';
}
