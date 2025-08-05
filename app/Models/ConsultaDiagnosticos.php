<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaDiagnosticos extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaDiagnosticos";
    protected $fillable = [
        'consulta',
        'diagnosticos_sindromaticos',
        'diagnosticos_etiologicos',
        'diagnosticos_diferenciales',
    ];
}
