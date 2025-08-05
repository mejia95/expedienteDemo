<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Consultas extends Model
{
        use HasFactory;

    protected $connection = "mongodb";
    protected $table="Consultas";
    protected $fillable = ['id_paciente','id_consultorio','contador_folio','estado','medico','consulta_original','copia'];
}
