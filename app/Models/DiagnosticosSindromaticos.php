<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class DiagnosticosSindromaticos extends Model
{
    protected $connection = "mongodb";
    protected $table="ConsultaDiagnosticoSindromatico";
    protected $guarded = [];
}
