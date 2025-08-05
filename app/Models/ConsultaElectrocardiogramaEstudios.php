<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaElectrocardiogramaEstudios extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaElectrocardiogramaEstudios";
    protected $fillable = [
        'link_electro',
        'archivoElect1',
        'archivoElect2',
        'archivoElect3',
        'descripcion',
        'ritmo',
        'frecuencia_cardiaca',
        'eje',
        'duracionQRS',
        'ondaP',
        'ondaT',
        'segmento',
        'intervaloPR',
        'intervaloQTC',
        'conclusion',
        'estudio',
        'guardada',
        'bandera_copia'
    ];
}
