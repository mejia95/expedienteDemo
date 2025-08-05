<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatGenero extends Model
{
    protected $connection = "mongodb";
    protected $table="CatGeneros";
}
