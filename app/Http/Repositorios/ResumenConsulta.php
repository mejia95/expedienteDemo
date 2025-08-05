<?php 

namespace App\Http\Repositorios;

use DB;
use App\Models\Consultorios;
use App\Models\Consultas;
use App\Models\UsuariosPerfiles;
use Illuminate\Support\Collection;
use Log;
use MongoDB\BSON\ObjectId;

class ResumenConsulta {

    public function ResumenConsulta ($id, $condicionPasante) {

        $resumen = Consultas::raw(function ($collection) use ($id,$condicionPasante) {
            return $collection->aggregate([
                [
                    '$lookup'=>[
                        'from'=>'DependenciasSS',
                        'localField'=>'id_consultorio',
                        'foreignField'=>'_id',
                        'as'=>'ConsultorioEspacio'
                    ]
                ],
                [
                    '$unwind' => [
                        'path'=> '$ConsultorioEspacio','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'PacientesAntecedentes',
                        'localField' => 'id_paciente',
                        'foreignField' => 'id_paciente',
                        'as' => 'antecedentes',
                        'pipeline'=>[
                            [
                                '$lookup'=>[
                                    'from'=>'enfermedades_frecuentes',
                                    'localField'=>'antecedentesPerPatologicos',
                                    'foreignField'=>'eID',
                                    'as'=>'NombresEnfermedades'

                                ]
                            ],
                            [
                                '$lookup'=>[
                                    'from'=>'Toxicomanias',
                                    'localField'=>'toxicomanias',
                                    'foreignField'=>'valor',
                                    'as'=>'Toxicomanias'

                                ]
                            ],

                        ]
                    ],
                ],
                       [
                    '$unwind' => [
                        'path'=> '$antecedentes','preserveNullAndEmptyArrays'=>true,                        
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracion',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionPiel',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_piel',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionOftalmologico',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_oftalmologico',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionRespiratorio',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_respiratorio',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionCardiovascular',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_cardiovascular',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionNeurologico',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_neurologico',
                    ],
                ], [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionGinecoObstetrico',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_ginecoobstetrico',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionAbdomen',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_abdomen',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaExploracionCabezaCuello',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion_cabeza_cuello',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaEstudios',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'estudios_padre',
                    ],
                ],
           /*     [
                    '$lookup' => [
                        'from' => 'ConsultaDiagnosticos',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'diagnostico',
                    ],
                ],*/
                [
                    '$lookup' => [
                        'from' => 'ConsultaDiagnosticoSindromatico',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'sindromaticos',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatDiagnosticos',
                        'localField' => 'sindromaticos.dId',
                        'foreignField' => 'dId',
                        'as' => 'diagnostico_sindromaticos',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaDiagnosticoEtiologicos',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'etiologicos',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatDiagnosticos',
                        'localField' => 'etiologicos.dId',
                        'foreignField' => 'dId',
                        'as' => 'diagnostico_etiologicos',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaDiagnosticoDiferenciales',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'diferenciales',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatDiagnosticos',
                        'localField' => 'diferenciales.dId',
                        'foreignField' => 'dId',
                        'as' => 'diagnostico_diferenciales',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaEstudiosLaboratorio',
                        'localField' => 'estudios_padre._id',
                        'foreignField' => 'estudio',
                        'as' => 'estudios_laboratorio',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaElectrocardiogramaEstudios',
                        'localField' => 'estudios_padre._id',
                        'foreignField' => 'estudio',
                        'as' => 'estudios_electrocardiograma',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaRadiografiaEstudios',
                        'localField' => 'estudios_padre._id',
                        'foreignField' => 'estudio',
                        'as' => 'estudios_radiografias',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaTratamiento',
                        'localField' => '_id',
                        'foreignField' => 'id_consulta',
                        'as' => 'tratamiento',
                    ],
                ],
                [
                    '$match' =>$condicionPasante,
                ],

            ]);
        });

        return $resumen;
    }

    public function InformacionMedico($medico, $consultorio){

        $medico_info = UsuariosPerfiles::raw(function ($collection) use ($medico) {
            return $collection->aggregate([
                [
                    '$lookup'=>[
                        'from'=>'users',
                        'localField'=>'usuarioId',
                        'foreignField'=>'_id',
                        'as'=>'users'
                    ]
                ],
                [
                    '$match' => [
                        '_id' => new ObjectId($medico)
                    ]
                ],
                [
                    '$project' => [                        
                        'consultorio'=>1,
                        'users.rol'=>1
                    ],
                ],
            ]);
        });

        if($medico_info[0]->users[0]['rol'] == 3 && $medico_info[0]->consultorio == $consultorio){
            return 1;
        }else{
            return 0;
        }
    }
}