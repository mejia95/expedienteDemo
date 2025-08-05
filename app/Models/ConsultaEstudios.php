<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
class ConsultaEstudios extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaEstudios";

    protected $fillable = ['consulta','bandera_copia'];

}
