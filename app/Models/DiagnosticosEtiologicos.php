<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class DiagnosticosEtiologicos extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaDiagnosticoEtiologicos";
    protected $guarded = [];
}
