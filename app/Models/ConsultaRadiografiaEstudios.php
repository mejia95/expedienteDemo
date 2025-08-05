<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;


class ConsultaRadiografiaEstudios extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaRadiografiaEstudios";
    protected $fillable = [
            'link_radiografia',
            'archivoRadio1',
            'archivoRadio2',
            'archivoRadio3',
            'partes_blandas',
            'partes_oseas',
            'campos_pulmonares',
            'silueta_cardiovascular',
            'indice_cardiotoracico',
            'conclusiones',
            'estudio',
            'guardada',
            'bandera_copia'
    ];
}
