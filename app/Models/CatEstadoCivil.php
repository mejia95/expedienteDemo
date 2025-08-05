<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatEstadoCivil extends Model
{
    protected $connection = "mongodb";
    protected $table="CatEstadoCivil";
}
