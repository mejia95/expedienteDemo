<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatDiagnosticos extends Model
{
    protected $connection = "mongodb";
    protected $table="CatDiagnosticos";
}
