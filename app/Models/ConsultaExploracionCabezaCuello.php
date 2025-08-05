<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionCabezaCuello extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaExploracionCabezaCuello";
    protected $fillable = [
        'CraneoCara',
        'CueroCabelludo',
        'RegionOrbitoNasal',
        'RegionOrofaringea',
        'InspeccionCuello',
        'PalpacionCuello',
        'PercusionCuello',
        'AuscultacionCuello',
        'consulta',
        'bandera_copia',
    ];
}
