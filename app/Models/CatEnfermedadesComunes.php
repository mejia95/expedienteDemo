<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CatEnfermedadesComunes extends Model
{
    protected $connection = "mongodb";
    protected $table="CatAntecedentesPerPatologicos";
}
