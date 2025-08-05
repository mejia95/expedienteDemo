<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionNeurologico extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionNeurologico";
    protected $fillable = [
        'Ocular',
        'Verbal',
        'Motora',
        'Total',
        'EstadoAlerta',
        'FuncionesMentalesSuperiores',
        'ParesCranales',
        'EsferaMotora',
        'EsferaSensitiva',
        'Reflejos',
        'PruebasEspeciales',
        'consulta',
        'bandera_copia',
    ];
}
