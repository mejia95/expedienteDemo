<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class MedicosImportaciones extends Model
{
    //
    protected $connection = "mongodb";
    protected $table="MedicosImportaciones";
    protected $fillable = [
        'creado_por',
        'lista_medicos_importados',
        'lista_medicos_no_importados',
        'estado',
        'eliminado_por'
    ];
}
