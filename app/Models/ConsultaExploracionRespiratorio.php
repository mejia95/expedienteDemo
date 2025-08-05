<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionRespiratorio extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionRespiratorio";
    protected $fillable = [
        'SaturacionOxigeno',
        'InspeccionRespiratorio',
        'PalpacionRespiratorio',
        'PercusionRespiratorio',
        'AuscultacionRespiratorio',
        'consulta',
        'bandera_copia',
    ];
}
