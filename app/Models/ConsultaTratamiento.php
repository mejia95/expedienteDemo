<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaTratamiento extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaTratamiento";
    protected $fillable = [
        'id_consulta',
        'id_consultorio',
        'plan_terapeutico',
        'ordenes_estudios',
        'indicaciones_receta',
        'arreglo_medicamento',
        'edad',
        'bandera_copia'
    ];
}
