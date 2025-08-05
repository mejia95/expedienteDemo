<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Toxicomanias extends Model
{
    protected $connection = "mongodb";
    protected $table="Toxicomanias";
}
