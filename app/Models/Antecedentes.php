<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Antecedentes extends Model
{
    use HasFactory;
    protected $connection = "mongodb";
    protected $table = "PacientesAntecedentes";
    protected $fillable = [
        'id_paciente',
        'cirugias_hospitalizaciones',
        'inmunizaciones',
        'antecedentesPerPatologicos',
        'antecedentesNoPatologicos',
        'antecedentesGinecoObs',
        'toxicomanias',
        'alergias',
        'cardiovascular',
        'respiratorio',
        'NefroUrologico',
        'Neurologico',
        'Hematologicos',
        'Ginecologicos',
        'Infectologicos',
        'Endocrinologicos',
        'Quirurgicos',
        'Alergicos',
        'SocioecEpide',
        'AntecedentesHeredo',
    ];
}
