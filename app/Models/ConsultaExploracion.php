<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracion extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracion";

    protected $fillable =
    [
        'tension_sistolica',
        'tension_diastolica',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'peso',
        'peso_unidad',
        'altura',
        'altura_unidad',
        'imc',
        'imc_unidad',
        'glucosa_sanguinea',
        'glucosa_unidad',
        'satuoxigeno',
        'motivo_consulta',
        'consulta',
        'bandera_copia',
    ];
}
