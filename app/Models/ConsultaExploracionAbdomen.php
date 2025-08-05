<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionAbdomen extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionAbdomen";
    protected $fillable = [
        'InspeccionAbdomen',
        'PalpacionAbdomen',
        'PercusionAbdomen',
        'AuscultacionAbdomen',
        'consulta',
        'bandera_copia'
    ];
}
