<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ConsultaExploracionOftalmologico extends Model
{
   protected $connection = "mongodb";
    protected $table="ConsultaExploracionOftalmologico";
    protected $fillable = [
        'AgudezaOjoDerecho',
        'AgudezaOjoIzquierdo',
        'RefraccionOjoDerecho',
        'RefraccionOjoIzquierdo',
        'PresionOcularOjoDerecho',
        'PresionOcularOjoIzquierdo',
        'ExploracionSegmentoAnterior',
        'ExploracionRetinayVitreo',
        'consulta',
        'bandera_copia',
    ];
}
