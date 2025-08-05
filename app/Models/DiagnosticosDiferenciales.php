<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class DiagnosticosDiferenciales extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaDiagnosticoDiferenciales";
    protected $guarded = [];
}
