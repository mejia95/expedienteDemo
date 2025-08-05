<?php
namespace App\Http\Repositorios;
use DB;
class EstadisticosRepositorio {
    private $pipeline_total_pacientes = [
        [
            '$project'=>['genero'=>1]
        ],
        [
            '$lookup'=>[
                'from'=>'CatGeneros',
                'localField'=>'genero',
                'foreignField'=>'GeneroValor',
                'as'=>'Generos'
            ]
        ],
        [
            '$unwind'=>'$Generos'
        ],
        [
            '$group'=>[
                '_id'=>null,
                'total'=>[
                    '$sum'=>1
                ],
                'totalHombres'=>['$sum'=>['$cond'=>[['$eq'=>['$genero',1]],1,0]]],
                'totalMujeres'=>['$sum'=>['$cond'=>[['$eq'=>['$genero',2]],1,0]]],
                'totalNoEspecificado'=>['$sum'=>['$cond'=>[['$not'=>['$in'=>['$genero',[2,1]]]],1,0]]],
            ]
        ],
        [
            '$addFields'=>[
                'totales'=>[
                    [
                        'title'=>'Total de pacientes',
                        'icon'=>'mdi-account-group',
                        'subtitle'=>'Pacientes registrados',
                        'value'=>'$total',
                        'bg'=>'bg-blue-lighten-5',
                        'color'=>'blue',
                    ],
                    [
                        'title'=>'Hombres',
                        'value'=>'$totalHombres',
                        'icon'=>'mdi-gender-male',
                        'subtitle'=>'Pacientes masculinos',
                        'bg'=>'bg-blue-lighten-5',
                        'color'=>'blue',
                    ],
                    [
                        'title'=>'Mujeres',
                        'value'=>'$totalMujeres',
                        'icon'=>'mdi-gender-female',
                        'subtitle'=>'Pacientes femeninas',
                        'bg' => 'bg-pink-lighten-5',
                        'color' => 'pink',
                    ],
                    [
                        'title'=>'Sin especificar',
                        'icon'=>'mdi-gender-male-female',
                        'subtitle'=>'Género no definido',
                        'value'=>'$totalNoEspecificado',
                        'bg'=>'bg-grey-lighten-4',
                        'color'=>'grey',
                    ],
                ]
            ]
        ],
        [
            '$project'=>['totales'=>1]
        ]
    ];

    public function obtenerPacientes (){
        $pipeline = $this->pipeline_total_pacientes;
        $pacientes = DB::table('PacientesPerfil')->raw(function($coleccion) use ($pipeline){
            return $coleccion->aggregate($pipeline);
        })->toArray();
        return $pacientes[0];
        //return [];
    }
}