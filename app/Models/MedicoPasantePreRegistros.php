<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MedicoPasantePreRegistros extends Model
{
    //
    protected $connection = "mongodb";
    protected $table="MedicoPasantePreRegistros";
    protected $fillable = [
        'usuarioId',
        'fecha_solicitud',
        'tiempo_expiracion_solicitud_segundos',
        'status_solicitud'

    ];

}
