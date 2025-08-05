<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatNacionalidad extends Model
{
    protected $connection = "mongodb";
    protected $table="CatNacionalidades";
}
