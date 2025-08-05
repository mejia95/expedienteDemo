<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pacientes extends Model
{
    protected $connection = "mongodb";
    protected $table="PacientesPerfil";
    protected $guarded = [];
}
