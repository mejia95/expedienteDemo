<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class ConsultaLaboratorioEstudios extends Model
{

    protected $connection = "mongodb";
    protected $table="ConsultaEstudiosLaboratorio";
    protected $fillable = [
            'link_laboratorio',
            'archivoLab1',
            'archivoLab2',
            'archivoLab3',
            'hematocrito',
            'leucocitos',
            'linfocitos',
            'monocitos',
            'volumen_corpuscular',
            'plaquetas',
            'glucemia',
            'urea',
            'creatinina',
            'sodio',
            'potasio',
            'cloro',
            'got',
            'gpt',
            'fosfatasa',
            'bilirrubina',
            'coagulograma',
            'ph',
            'co2',
            'hco3',
            'po2',
            'saturacion_oxigeno',
            'anion',
            'orina',
            'glucosa',
            'hemocultivo',
            'urocultivo',
            'descripcion_urocultivo',
            'estudio',
            'guardada',
            'bandera_copia'
    ];
}
