<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionPiel extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionPiel";
    protected $fillable = [
        'aspecto',
        'descripcion_piel',
        'distribucion_pilosa',
        'lesiones',
        'topografia',
        'sintomas',
        'mucosas',
        'unas',
        'tejido_celular_subcutaneo',
        'consulta',
        'piel',
        'bandera_copia'
    ];
}
