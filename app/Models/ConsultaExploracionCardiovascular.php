<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionCardiovascular extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionCardiovascular";
    protected $fillable = [
        'descripcion_cardiovascular',
        'consulta',
        'bandera_copia'
    ];
}
