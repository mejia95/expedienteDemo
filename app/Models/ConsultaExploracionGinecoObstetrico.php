<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionGinecoObstetrico extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionGinecoObstetrico";
    protected $fillable = [
        'Mamas',
        'Abdomen',
        'Pelvis',
        'consulta',
        'bandera_copia',
    ];
}
