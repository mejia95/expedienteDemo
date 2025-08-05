<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatTipoSangre extends Model
{
    protected $connection = "mongodb";
    protected $table="CatTipoSangre";
}
