<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultas;
use App\Models\UsuariosPerfiles;
use App\Models\DependenciasSS;
use App\Models\Pacientes;
use App\Models\Antecedentes;
use App\Models\ConsultaExploracion;
use App\Models\ConsultaExploracionAbdomen;
use App\Models\ConsultaExploracionCabezaCuello;
use App\Models\ConsultaExploracionCardiovascular;
use App\Models\ConsultaExploracionNeurologico;
use App\Models\ConsultaExploracionGinecoObstetrico;
use App\Models\ConsultaExploracionOftalmologico;
use App\Models\ConsultaExploracionPiel;
use App\Models\ConsultaExploracionRespiratorio;
use App\Models\ConsultaEstudios;
use App\Models\ConsultaLaboratorioEstudios;
use App\Models\ConsultaElectrocardiogramaEstudios;
use App\Models\ConsultaRadiografiaEstudios;
use App\Models\ConsultaDiagnosticos;
use App\Models\DiagnosticosDiferenciales;
use App\Models\DiagnosticosEtiologicos;
use App\Models\DiagnosticosSindromaticos;
use App\Models\ConsultaTratamiento;
use App\Http\Requests\ExploracionRequest;
use App\Http\Requests\EstudiosRequest;
use App\Http\Repositorios\ExploracionesyConsulta;
use App\Http\Repositorios\Estudios;
use App\Http\Repositorios\Diagnosticos;
use App\Http\Repositorios\AntecedentesRepositorio;
use App\Http\Repositorios\ResumenConsulta;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;
use Auth;
use Log;
use Session;

class ConsultasController extends Controller
{
    private $estudios,$exploracion;
    public function __construct(Estudios $estudios,ExploracionesyConsulta $exploracion, Diagnosticos $diagnostico,AntecedentesRepositorio $antecedentes_repositorio, ResumenConsulta $resumen)
    {
        $this->estudios = $estudios;
        $this->exploracion = $exploracion;
        $this->diagnostico = $diagnostico;
        $this->antecedentes_repositorio = $antecedentes_repositorio;
        $this->resumen = $resumen;

    }

    public function ConsultaConsultorios(Request $request, $id){
        //if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        //if($usuario->rol != 3){abort(500);}

        $consultorio_seleccionado = Consultas::where('_id',new ObjectId($id))->first();
        $seleccion = null;

        if($consultorio_seleccionado->id_consultorio){
            
            $seleccion = Consultas::raw(function ($collection) use ($id) {
                return $collection->aggregate([
                    [
                        '$lookup' => [
                            'from' => 'DependenciasSS',
                            'localField' => 'id_consultorio',
                            'foreignField' => '_id',
                            'as' => 'consultorio',
                        ],
                    ],
                    [
                        '$unwind' => '$consultorio'
                    ],
                    [
                        '$match' => [
                            '_id' =>  new ObjectId($id),
                        ],
                    ],
                    [
                        '$project' => [
                            'consultorio._id' => '$consultorio._id',
                            'consultorio.nombre' => '$consultorio.nombre',
                        ],
                    ],
                ]);
            });
            $seleccion = $seleccion[0]->consultorio;

        }

        $consultorios = DependenciasSS::select('_id','nombre')->get();

        return response()->json(['consultorios' => $consultorios,'seleccion'=>$seleccion]);

    }

    public function create(Request $request)
    {
        // if(Session::get('RolUsuario') == 1 || Session::get('RolUsuario') == 6){
        //     abort(510);
        // }


        try{
            $folio_contador = 0;
        $usuario = Auth::user();
        $medico = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->_id))->first();
        $consultorio = DependenciasSS::where('_id', $medico->consultorio)->first();
                
        $consulta = Consultas::where('id_paciente',new ObjectId($request->id))->whereNull('copia')->where('estado',1)->first();

        if($consulta){
            Antecedentes::where('id_consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracion::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionAbdomen::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionCabezaCuello::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionCardiovascular::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionNeurologico::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionGinecoObstetrico::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionOftalmologico::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionPiel::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaExploracionRespiratorio::where('consulta',new ObjectId($consulta->_id))->delete();
            $estudios = ConsultaEstudios::where('consulta',new ObjectId($consulta->_id))->first();
            if($estudios){
                ConsultaLaboratorioEstudios::where('estudio',$estudios->_id)->delete();
                ConsultaElectrocardiogramaEstudios::where('estudio',$estudios->_id)->delete();
                ConsultaRadiografiaEstudios::where('estudio',$estudios->_id)->delete();
            }
            ConsultaEstudios::where('consulta',new ObjectId($consulta->_id))->delete();
            ConsultaDiagnosticos::where('consulta',new ObjectId($consulta->_id))->delete();
            DiagnosticosDiferenciales::where('consulta',new ObjectId($consulta->_id))->delete();
            DiagnosticosEtiologicos::where('consulta',new ObjectId($consulta->_id))->delete();
            DiagnosticosSindromaticos::where('consulta',new ObjectId($consulta->_id))->delete();
            Consultas::where('_id',new ObjectId($consulta->_id))->delete();
        }

        $contador = Consultas::select('contador_folio')->orderBy('_id','desc')->first();

        if($contador){
            $folio_contador = $contador->contador_folio + 1;
        }else{
            $folio_contador = 1;
        }

        $consulta = Consultas::create([
            'id_paciente' => new \MongoDB\BSON\ObjectId($request->id),
            'contador_folio' =>  $folio_contador,
            'medico'=> new ObjectId($medico->_id),
            'id_consultorio'=> $consultorio ? new ObjectId($consultorio->_id) : null,
            'estado' => 1,
        ]);

       if($consulta){
            return Inertia::location("/consulta/nuevo/$consulta->id");
        }else{
            return Inertia::location("/pacientes/mis-pacientes");
            //return response()->json(['message' => 'Error'], 500);
        }
        }catch(\Throwable $e){
            return $e;
        }

        
    }

    public function ConsultaDatosPaciente(Request $request, $id){
    //    if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}

         $consulta_paciente = Consultas::raw(function ($collection) use ($id) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'PacientesPerfil',
                        'localField' => 'id_paciente',
                        'foreignField' => '_id',
                        'as' => 'id_consulta',
                    ],
                ],
                [
                    '$unwind' => '$id_consulta'
                ],
                [
                    '$match' => [
                        '_id' =>  new \MongoDB\BSON\ObjectId($id),
                    ],
                ],
                [
                    '$project' => [
                        'fecha_nacimiento' => '$id_consulta.fecha_nacimiento',
                    ],
                ],
            ]);
        });

        $fecha = $consulta_paciente[0]->fecha_nacimiento;
         $fechaNacimiento = Carbon::parse($fecha);

        $hoy = Carbon::now();
        $diferencia = $fechaNacimiento->diff($hoy);

        $edad = $diferencia->y.' '.'años'.' '.$diferencia->m.' '.'meses'.' '.$diferencia->d.' '.'días';

        $consulta_paciente = Pacientes::raw(function ($collection) use ($id, $edad) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'Consultas',
                        'localField' => '_id',
                        'foreignField' => 'id_paciente',
                        'as' => 'id_consulta',
                       
                    ],
                ],
                [
                    '$unwind' => '$id_consulta'
                ],
                [
                    '$lookup' => [
                        'from' => 'CatGeneros',
                        'localField' => 'genero',
                        'foreignField' => 'GeneroValor',
                        'as' => 'nombre_genero',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatEstadoCivil',
                        'localField' => 'estado_civil',
                        'foreignField' => 'valor',
                        'as' => 'estado_civil',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatTipoSangre',
                        'localField' => 'tipo_sangre',
                        'foreignField' => 'valor',
                        'as' => 'tipo_sangre',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatNacionalidades',
                        'localField' => 'nacionalidad',
                        'foreignField' => 'nacionalidadCod',
                        'as' => 'nacionalidad',
                    ],
                ],
                [
                    '$match' => [
                        'id_consulta._id' =>  new \MongoDB\BSON\ObjectId($id),
                    ],
                ],
                [
                    '$unwind' => '$nombre_genero'
                ],
                [
                    '$unwind' => '$estado_civil'
                ],
                [
                    '$unwind' => [
                        'path'=> '$tipo_sangre','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$unwind' => '$nacionalidad'
                ],
                

                [
                    '$project' => [
                        '_id' => '$_id',
                        'consulta_duplicada'=>['$ifNull'=>['$id_consulta.consulta_original','No']],
                        'nombre'=> '$nombre',
                        'primer_apellido' => '$primer_apellido',
                        'segundo_apellido' => '$segundo_apellido',
                        'curp' => '$CURP',
                        'etiqueta_genero' => '$nombre_genero.GeneroEtiqueta',
                        'fecha_nacimiento' => '$fecha_nacimiento',
                        'edad' =>  $edad,
                        'ocupacion' => '$ocupacion',
                        'estado_civil' => '$estado_civil.etiqueta',///-----------------
                        'nacionalidad' => '$nacionalidad.nacionalidadPais',
                        'tipo_sangre' => '$tipo_sangre.etiqueta',
                        'correo' => '$correo',
                        'telefono' => '$telefono',
                        'telefono_celular' => '$telefono_celular'
                    ],
                ],
            ]);
        });

        if($consulta_paciente){
            $datos[] = [
                '_id' => $consulta_paciente[0]->_id,
                'consulta_duplicada' => $consulta_paciente[0]->consulta_duplicada=='No'? 'No':'Si',
                        'nombre'=> $consulta_paciente[0]->nombre,
                        'primer_apellido' => $consulta_paciente[0]->primer_apellido,
                        'segundo_apellido' => $consulta_paciente[0]->segundo_apellido,
                        'curp' => $consulta_paciente[0]->curp,
                        'etiqueta_genero' => $consulta_paciente[0]->etiqueta_genero,
                        'fecha_nacimiento' => date_format(date_create($consulta_paciente[0]->fecha_nacimiento),'d-m-Y') ,
                        'edad' =>  $consulta_paciente[0]->edad,
                        'ocupacion' => $consulta_paciente[0]->ocupacion,
                        'estado_civil' => $consulta_paciente[0]->estado_civil,
                        'nacionalidad' => $consulta_paciente[0]->nacionalidad,
                        'tipo_sangre' => $consulta_paciente[0]->tipo_sangre,
                        'correo' => $consulta_paciente[0]->correo,
                        'telefono' => $consulta_paciente[0]->telefono,
                        'telefono_celular' => $consulta_paciente[0]->telefono_celular
            ];
            return $datos;
        }else{
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }
    }

    public function ObtenerRegistrarExploracion(ExploracionRequest $request)
    {
       // if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        try{
            if(!$request->consulta){abort(500);}
            $consulta = $request->consulta;
            $guardarInformacion =  $request->guardar ? $request->guardar : 0;
            $copia = 0;
            $DatosConsulta = Consultas::where('_id',new ObjectId($consulta))->first();
            
            if(!$DatosConsulta){abort(500);}
            //return $guardarInformacion;
            if($DatosConsulta->copia == 1){
                $copia = 1;
            }
            $no_consulta = $DatosConsulta->_id;

            //Datos
            $tension_sistolica = $request->tension_sistolica ? $request->tension_sistolica :null;
            $tension_diastolica = $request->tension_diastolica ? $request->tension_diastolica :null;
            $frecuencia_cardiaca = $request->frecuencia_cardiaca ? $request->frecuencia_cardiaca :null;
            $frecuencia_respiratoria = $request->frecuencia_respiratoria ? $request->frecuencia_respiratoria :null;
            $temperatura = $request->temperatura ? $request->temperatura :null;

            $peso = $request->peso ? $request->peso :null;
            $peso_unidad = $request->peso_unidad ? $request->peso_unidad :null;
            $altura = $request->altura ? $request->altura :null;
            $altura_unidad = $request->altura_unidad ? $request->altura_unidad :null;
            $imc = $request->imc ? $request->imc :null;
            $imc_unidad = $request->imc_unidad ? $request->imc_unidad :null;

            //Estos son los nuevos campos agregados
            $glucosa_sanguinea = $request->glucosa_sanguinea ? $request->glucosa_sanguinea : null;
            $glucosa_unidad = $request->glucosa_unidad ? $request->glucosa_unidad : null;
            $satuoxigeno = $request->satuoxigeno ? $request->satuoxigeno : null;
            /////////////////////////////////////////

            $motivo_consulta = $request->motivo_consulta ? $request->motivo_consulta :null;
            $piel = $request->piel ? $request->piel :null;
            $cabeza = $request->cabeza ? $request->cabeza :null;
            $oftalmologico = $request->oftalmologico ? $request->oftalmologico :null;
            $cuello = $request->cuello ? $request->cuello :null;
            $respiratorio = $request->respiratorio ? $request->respiratorio :null;
            $cardiovascular = $request->cardiovascular ? $request->cardiovascular :null;
            $abdomen = $request->abdomen ? $request->abdomen :null;
            $neurologico = $request->neurologico ? $request->neurologico :null;
            $GinecoObstetrico = $request->GinecoObstetrico ? $request->GinecoObstetrico :null;


            $exploracion = $this->exploracion->setConsulta($no_consulta)
            ->RegistrarConsultaGral(
                $tension_sistolica,
                $tension_diastolica,
                $frecuencia_cardiaca,
                $frecuencia_respiratoria,
                $temperatura,
                $peso,
                $peso_unidad,
                $altura,
                $altura_unidad,
                $imc,
                $imc_unidad,
                $glucosa_sanguinea,
                $glucosa_unidad,
                $satuoxigeno,
                $motivo_consulta,
                $copia,
            )
            ->RegistrarPiel($piel,$copia)
            ->RegistrarCabezaYCuello($cabeza,$copia)
            ->RegistrarOftalmologico($oftalmologico,$copia)
            ->RegistrarRespiratorio($respiratorio,$copia)
            ->RegistrarCardiovascular($cardiovascular,$copia)
            ->RegistrarAbdomen($abdomen,$copia)
            ->RegistrarNeurologico($neurologico,$copia)
            ->RegistrarGinecoObstetrico($GinecoObstetrico,$copia)
            ;
            //dd($request);
            return response()->json(['message'=>'<p>Los datos de la sección de <b>Exploración y Consulta</b> se han guardado correctamente.</p>']);
        }catch(\Throwable $e){
            return response()->json(['message'=>"Ha ocurrio un error al registrar la información por favor intente más tarde."],500);
        }
    }

    public function ObtenerExploracionTodos(Request $request){
        ///if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        $consulta = $request->consulta;
        if(!$consulta){abort(500);}
        $pipelineExploracionTodos = [
            [
                '$lookup'=>[
                    'from'=>'Consultas',
                    'localField'=>'consulta',
                    'foreignField'=>'_id',
                    'as'=>'InfoConsulta',
                    'pipeline'=>[
                        [
                            '$project'=>['estado'=>1]
                        ]
                    ]
                ]
            ],
            [
                '$unwind' => [
                    'path'=> '$InfoConsulta','preserveNullAndEmptyArrays'=>true
                ]
            ],
            [
                '$lookup'=>[
                    'from'=>'ConsultaExploracionPiel',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionPiel'
                ],
            ],
            [
                '$lookup'=>[
                    'from'=>'ConsultaExploracionCabezaCuello',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionCabezaCuello'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionOftalmologico',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionOftalmologico'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionRespiratorio',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionRespiratorio'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionCardiovascular',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionCardiovascular'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionAbdomen',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionAbdomen'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionNeurologico',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionNeurologico'
                ],
            ],[
                '$lookup'=>[
                    'from'=>'ConsultaExploracionGinecoObstetrico',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionGinecoObstetrico'
                ],
            ],
            [
                '$addFields'=>[
                    'peso'=>[
                        '$toString'=>'$peso'
                    ],
                    'altura'=>[
                         '$toString'=>'$altura'
                    ],
                    'estado_consulta'=>'$InfoConsulta.estado'
                ]
            ],
            [
                '$match'=>['consulta'=>new ObjectId($request->consulta)]
            ],
            [
                '$project'=>['InfoConsulta'=>0]
            ]
        ];

        $obtenerExploracionTodos = ConsultaExploracion::raw(function($collection)use($pipelineExploracionTodos){
            return $collection->aggregate($pipelineExploracionTodos);
        });
        if(sizeof($obtenerExploracionTodos) > 0){
            return response()->json(['ExploracionTodos'=>$obtenerExploracionTodos[0]]);
        }
    }

        function ActualizarConsulta(Request $request){
            $id_consulta = $request->id_consulta;
            $consultorio = $request->seleccion;
            if($consultorio){
                $update_consultas = Consultas::find($id_consulta);
                $update_consultas->id_consultorio = new \MongoDB\BSON\ObjectId($consultorio);
                $update_consultas->save();
        }
    }

    public function ObtenerExploracionPielTejidoDatos(Request $request){
        if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        $consulta = $request->consulta;
        if(!$consulta){abort(500);}
        $pipelineExploracionYPiel = [
            [
                '$lookup'=>[
                    'from'=>'ConsultaExploracionPiel',
                    'localField'=>'consulta',
                    'foreignField'=>'consulta',
                    'as'=>'ExploracionPiel'
                ],
            ],
            [
                '$addFields'=>[
                    'peso'=>[
                        '$toString'=>'$peso'
                    ],
                    'altura'=>[
                         '$toString'=>'$altura'
                    ]
                ]
            ],
            [
                '$match'=>['consulta'=>new ObjectId($request->consulta)]
            ]
        ];

        $obtenerExploracionYPiel = ConsultaExploracion::raw(function($collection)use($pipelineExploracionYPiel){
            return $collection->aggregate($pipelineExploracionYPiel);
        });

        return response()->json(['ExploracionYPiel'=>$obtenerExploracionYPiel[0]]);
    }

    public function ObtenerExploracionCabezaCuello(Request $request){
        if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        $consulta = $request->consulta;
        if(!$consulta){abort(500);}
        $pipelineCabezaCuello = [
            [
                '$match'=>['consulta'=>new ObjectId($request->consulta)]
            ]
        ];

        $obtenerExploracionCabezaCuello = ConsultaExploracionCabezaCuello::raw(function($collection)use($pipelineCabezaCuello){
            return $collection->aggregate($pipelineCabezaCuello);
        });

        return response()->json(['CabezaCuello'=>$obtenerExploracionCabezaCuello[0]]);
    }

     public function ObtenerEstudioLaboratorioConsulta(EstudiosRequest $request)
    {
        
       // if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        try{
            
            if(!$request->consulta){abort(500);}

            $consulta = $request->consulta;
            $bandera_copia = 0;
            $guardarInformacion =  $request->guardar ? $request->guardar : 0;
            $DatosConsulta = Consultas::where('_id',new ObjectId($consulta))->first();

            if($DatosConsulta->copia == 1){
                $bandera_copia = 1;
            }
            if(!$DatosConsulta){abort(500);}
            //return $guardarInformacion;
            $no_consulta = $DatosConsulta->_id;
            if($guardarInformacion<1){
                $estudios = ConsultaEstudios::raw(function ($collection) use ($no_consulta) {
                    return $collection->aggregate([
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaEstudiosLaboratorio',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Laboratorio'
                            ]
                        ],
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaElectrocardiogramaEstudios',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Electrocardiograma'
                            ]
                        ],
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaRadiografiaEstudios',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Radiografia'
                            ]
                        ],
                        ['$match'=>[
                            'consulta'=>new ObjectId($no_consulta)
                            ]
                        ],
                ]);
                });
                //$estudios= ConsultaEstudios::where('consulta',new ObjectId($DatosConsulta->_id))->first();
                return response()->json(['estudios'=>$estudios]);
            }
            //Datos para registrar los estudios
            // Si no existen , solo se consulta la información
            $datos_estudios_laboratorio = $request->datos_laboratorio ? $request->datos_laboratorio : null;

            $datos_estudios_electrocardiograma = $request->datos_electro ? $request->datos_electro : null;
            $datos_estudios_radiografia = $request->datos_radiografia ? $request->datos_radiografia : null;
            $datos_estudios_otros = $request->otros ? $request->otros : null;

            $archivoLab1 = $request->file('datos_laboratorio.archivoLab1') ? $request->file('datos_laboratorio.archivoLab1') :null;
            $archivoLab2 = $request->file('datos_laboratorio.archivoLab2') ? $request->file('datos_laboratorio.archivoLab2') :null;
            $archivoLab3 = $request->file('datos_laboratorio.archivoLab3') ? $request->file('datos_laboratorio.archivoLab3') :null;
            $archivoElect1 = $request->file('datos_electro.archivoElect1') ? $request->file('datos_electro.archivoElect1') :null;
            $archivoElect2 = $request->file('datos_electro.archivoElect2') ? $request->file('datos_electro.archivoElect2') :null;
            $archivoElect3 = $request->file('datos_electro.archivoElect3') ? $request->file('datos_electro.archivoElect3') :null;
            $archivoRadio1 = $request->file('datos_radiografia.archivoRadio1') ? $request->file('datos_radiografia.archivoRadio1') :null;
            $archivoRadio2 = $request->file('datos_radiografia.archivoRadio2') ? $request->file('datos_radiografia.archivoRadio2') :null;
            $archivoRadio3 = $request->file('datos_radiografia.archivoRadio3') ? $request->file('datos_radiografia.archivoRadio3') :null;
           // dd($request);

            $existeRegistroEstudios = ConsultaEstudios::where('consulta',new ObjectId($DatosConsulta->_id))->first();
            if(!$existeRegistroEstudios){$existeRegistroEstudios = ConsultaEstudios::create(['consulta'=>new ObjectId($DatosConsulta->_id)]);}

            //return $existeRegistroEstudios;
            $insertarInformacionLaboratorio = $this->estudios
                ->insertarEstudio($consulta,$existeRegistroEstudios->_id,$datos_estudios_laboratorio,$datos_estudios_electrocardiograma,$datos_estudios_radiografia,$datos_estudios_otros, $bandera_copia)
                ->RegistrarLaboratorio($archivoLab1,$archivoLab2,$archivoLab3, $bandera_copia)
                ->RegistrarElectrocardiograma($archivoElect1,$archivoElect2,$archivoElect3, $bandera_copia)
                ->RegistrarRadiografia($archivoRadio1,$archivoRadio2,$archivoRadio3, $bandera_copia);
            $estudios = ConsultaEstudios::raw(function ($collection) use ($no_consulta) {
                    return $collection->aggregate([
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaEstudiosLaboratorio',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Laboratorio'
                            ]
                        ],
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaElectrocardiogramaEstudios',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Electrocardiograma'
                            ]
                        ],
                        [
                        '$lookup'=>[
                            'from' => 'ConsultaRadiografiaEstudios',
                            'localField'=>'_id',
                            'foreignField'=>'estudio',
                            'as'=>'Radiografia'
                            ]
                        ],
                        ['$match'=>[
                            'consulta'=>new ObjectId($no_consulta)
                            ]
                        ],
                ]);
                });
                return response()->json(['estudios'=>$estudios,'message'=>'<p>Los datos de la sección <strong>Estudios</strong> se han guardado correctamente.</p>']);
            //return response()->json(['estudios'=>$estudios,'message'=>'<p>Se ha guardado correctamente la información de la sección <strong>Estudios</strong></p>.']);
        }catch(\Throwable $e){
            return response()->json(['message'=>'Ha ocurrio un error, por favor intente más tardeds. '.$e],500);
        }
    }

    public function ObtenerDiagnostico(Request $request){
        if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        if(!$request->consulta){abort(500);}
        $consulta = $request->consulta;
        $diagnosticos_sindromaticos = null;
        $diagnosticos_etiologicos = null;
        $diagnosticos_diferenciales = null;

        // $DiagnosticoConsulta = ConsultaDiagnosticos::where('consulta',new ObjectId($consulta))->first();
        // $diagnosticos_sindromaticos = $DiagnosticoConsulta->diagnosticos_sindromaticos;
        // $diagnosticos_etiologicos = $DiagnosticoConsulta->diagnosticos_etiologicos;
        // $diagnosticos_diferenciales = $DiagnosticoConsulta->diagnosticos_diferenciales;

        $diagnosticos_sindromaticos = DiagnosticosSindromaticos::where('consulta',new ObjectId($consulta))->pluck('dId');
        $diagnosticos_etiologicos = DiagnosticosEtiologicos::where('consulta',new ObjectId($consulta))->pluck('dId');
        $diagnosticos_diferenciales = DiagnosticosDiferenciales::where('consulta',new ObjectId($consulta))->pluck('dId');

        return response()->json([
            'diagnosticos_sindromaticos'=>$diagnosticos_sindromaticos,
            'diagnosticos_etiologicos'=>$diagnosticos_etiologicos,
            'diagnosticos_diferenciales'=>$diagnosticos_diferenciales
        ]);

     }

    public function GuardarDiagnostico(Request $request){
       // if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}
        if(!$request->consulta){abort(500);}

        try{
            $bandera_copia = 0;
            $consulta = $request->consulta;
            $diagnosticos_sindromaticos = $request->diagnosticos_sindromaticos;
            $diagnosticos_etiologicos = $request->diagnosticos_etiologicos;
            $diagnosticos_diferenciales = $request->diagnosticos_diferenciales;

            $consulta_estado = Consultas::where('_id',new ObjectId($consulta))->first();

            if($consulta_estado->copia == 1){
                $bandera_copia = 1;
            }
           //$this->diagnostico->DiagnosticoConsulta($consulta,$diagnosticos_sindromaticos,$diagnosticos_etiologicos,$diagnosticos_diferenciales)->Diagnostico()->Sindromaticos()->Etiologicos()->Diferenciales()->guardar();
            $result_sindromaticos = $this->diagnostico->Sindromaticos($consulta, $diagnosticos_sindromaticos,$bandera_copia);
            $result_etiologicos = $this->diagnostico->Etiologicos($consulta, $diagnosticos_etiologicos,$bandera_copia);
            $result_diferenciales = $this->diagnostico->Diferenciales($consulta, $diagnosticos_diferenciales,$bandera_copia);
            return response()->json(['message'=>"<p>Los datos de la sección <strong>Diagnósticos</strong> se han guardado correctamente.</p>"]);
        }catch(\Throwable $e){
            return response()->json(['message'=>$e->getMessage()],500);
        }

    }

    function AgregarTratamiento(Request $request)
    {
        $usuario = Auth::user();
        $id_consulta = $request->id_consulta;
        $plan_terapeutico = $request->plan_terapeutico;
        $ordenes_estudios = $request->ordenes_estudios;
        $indicaciones_receta = $request->indicaciones_receta;
        $arreglo_medicamento = $request->arreglo_medicamento;
        $edad = $request->edad;
        $consultorio = $request->consultorio;

        $validator = Validator::make($request->all(), [
            'plan_terapeutico' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'ordenes_estudios' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('plan_terapeutico')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Plan terapéutico. </strong>(Números, emojis, etc)</p>";
            }
            if ($errors->has('ordenes_estudios')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Órdenes de estudios. </strong>(Números, emojis, etc)</p>";
            }
            return response()->json(array('message'=>$message),422);
        }
        $bandera_copia = 0;

        $existenParametrosObligatorios = ConsultaExploracion::where('consulta',new ObjectId($id_consulta))->whereNotNull('peso')->whereNotNull('temperatura')->whereNotNull('altura')->whereNotNull('motivo_consulta')->count();
       // return $existenParametrosObligatorios;
        //return response()->json(['message'=>$existenParametrosObligatorios],500);
        if($existenParametrosObligatorios < 1 ){
            return response()->json(['message'=>'<p>Los campos obligatorios de la consulta aún no han sido guardados. <strong>(Peso, Temperatura, Altura, Motivo de consulta)</strong></p>'],500);
        }

        $inf_consulta = Consultas::where('_id',$id_consulta)->first();
        if($inf_consulta->id_consultorio){

            $tratamiento_exist = ConsultaTratamiento::where('id_consulta',new ObjectId($id_consulta))->first();
            if($inf_consulta->copia == 1){
                $bandera_copia = 1;
            }

            $tratamiento_campos = [];
            $tratamiento_campos['id_consulta'] = new \MongoDB\BSON\ObjectId($id_consulta);
            $tratamiento_campos['plan_terapeutico'] = $plan_terapeutico;
            $tratamiento_campos['ordenes_estudios'] = $ordenes_estudios;
            $tratamiento_campos['indicaciones_receta'] = $indicaciones_receta;
            $tratamiento_campos['arreglo_medicamento'] = $arreglo_medicamento;
            $tratamiento_campos['bandera_copia'] = $bandera_copia;
            $tratamiento_campos['edad'] = $edad;


            if($tratamiento_exist){
                $tratamiento_exist->update($tratamiento_campos);
            }else{
                $tratamiento = ConsultaTratamiento::create($tratamiento_campos);
            }
            if($inf_consulta->consulta_original){
                $original = Consultas::where('_id',new ObjectId($inf_consulta->consulta_original))->first();                  
                $original->update(['estado'=> 0]);
            }

                $inf_consulta->estado = $inf_consulta->estado == 1 ? 2 : $inf_consulta->estado;
                $inf_consulta->save();

                $paciente = Pacientes::where('_id',new ObjectId($inf_consulta->id_paciente))->first();
                $paciente->consultorio = new ObjectId($inf_consulta->id_consultorio);
                $paciente->updated_at = date("Y-m-d H:i:s");
                $paciente->updated_by = new ObjectId($usuario->id);
                $paciente->save();
                return response()->json(array('message'=>"Se ha guardado la información correctamente"),200);

        }else{

            //return response()->json(['message' => 'Ha ocurrido un error al registrar la consulta'], 500);
        }

    }

    public function ObtenerTratamiento(Request $request, $id){

            $tratamiento = null;
            $tratamiento = ConsultaTratamiento::where('id_consulta',new ObjectId($id))->first();
            if($tratamiento){
                return response()->json([
                    'tratamiento'=>$tratamiento
                ]);
            }
    }

    public function ResumenConsulta(Request $request, $id)
    {

        $usuario = Auth::user();

        $condicionPasante=['_id'=>new ObjectId($id)];

        if($usuario->rol != 1 && $usuario->rol != 6){
             $medico = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->_id))->first();
            if($usuario->rol == 3){
                $condicionPasante=['id_consultorio'=>new ObjectId($medico->consultorio),'_id'=>new ObjectId($id)];
            }
        }

        $consulta_paciente = Pacientes::raw(function ($collection) use ($id) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'Consultas',
                        'localField' => '_id',
                        'foreignField' => 'id_paciente',
                        'as' => 'id_consulta',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatGeneros',
                        'localField' => 'genero',
                        'foreignField' => 'GeneroValor',
                        'as' => 'nombre_genero',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatEstadoCivil',
                        'localField' => 'estado_civil',
                        'foreignField' => 'valor',
                        'as' => 'estado_civil',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatTipoSangre',
                        'localField' => 'tipo_sangre',
                        'foreignField' => 'valor',
                        'as' => 'tipo_sangre',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatNacionalidades',
                        'localField' => 'nacionalidad',
                        'foreignField' => 'nacionalidadCod',
                        'as' => 'nacionalidad',
                    ],
                ],

                [
                    '$unwind' => '$nombre_genero'
                ],
                [
                    '$unwind' => '$estado_civil'
                ],
                [
                    '$unwind' => [
                        'path'=> '$id_consulta','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                            '$lookup'=>[
                                'from'=>'ConsultaTratamiento',
                                'localField'=>'id_consulta._id',
                                'foreignField'=>'id_consulta',
                                'as'=>'ConsultaEdad'
                            ]
                ],
                [
                    '$unwind' => [
                        'path'=> '$ConsultaEdad','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$unwind' => [
                        'path'=> '$tipo_sangre','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$unwind' => '$nacionalidad'
                ],

                [
                    '$match' => [
                        'id_consulta._id' =>  new \MongoDB\BSON\ObjectId($id),
                    ],
                ],
                [
                    '$project' => [
                        '_id' => '$_id',
                        'nombre'=> '$nombre',
                        'primer_apellido' => '$primer_apellido',
                        'segundo_apellido' => '$segundo_apellido',
                        'curp' => '$CURP',
                        'etiqueta_genero' => '$nombre_genero.GeneroEtiqueta',
                        'fecha_nacimiento' => '$fecha_nacimiento',
                        'edad' =>  '$ConsultaEdad.edad',
                        'ocupacion' => '$ocupacion',
                        'estado_civil' => '$estado_civil.etiqueta',///-----------------
                        'nacionalidad' => '$nacionalidad.nacionalidadPais',
                        'tipo_sangre' => '$tipo_sangre.etiqueta',
                        'correo' => '$correo',
                        'telefono' => '$telefono',
                        'telefono_celular' => '$telefono_celular'
                    ],
                ],
            ]);
        });

         $resumen =  $this->resumen->ResumenConsulta($id, $condicionPasante);

        if(sizeof($resumen)<1){
            return response()->json(['message'=>'No existe la consulta o no tienes permisos'],500);
        }

        $ultimo_dia = Carbon::parse($resumen[0]->created_at)->copy()->addDays(3)->translatedFormat('Y-m-d');
        $hoy = Carbon::now()->format('Y-m-d');
        if($resumen[0]->consulta_original && $resumen[0]->estado == 2){
            $btn_modificar = 1;            
        }else{
            $btn_modificar = (!$resumen[0]->copia && $resumen[0]->estado == 2 || $resumen[0]->copia && $resumen[0]->estado == 2) && $ultimo_dia >= $hoy ? 0 : 1;  
        }

        $medico_formacion = $this->resumen->InformacionMedico($resumen[0]->medico, $medico->consultorio);
//return $resumen[0]->ConsultorioEspacio['nombre'];
        return response()->json([
            'btn_modificar' => $usuario->rol == 4 && $medico_formacion ? $btn_modificar : 1,            
            'paciente' => $consulta_paciente[0],
            'antecedentes' => $resumen[0]->antecedentes,
            'consulta_original' => $resumen[0]->consulta_original,
            'created_at' => Carbon::parse($resumen[0]->created_at)->translatedFormat('d/m/Y H:i:s'),        
            'consultorio' => sizeof($resumen[0]->ConsultorioEspacio) >0 ? $resumen[0]->ConsultorioEspacio['nombre'] : [],
            'exploracion' => sizeof($resumen[0]->exploracion)>0 ? $resumen[0]->exploracion[0]:[] ,
            'exploracion_piel' => sizeof($resumen[0]->exploracion_piel)>0 ? $resumen[0]->exploracion_piel[0] : [],
            'exploracion_cabeza_cuello' => sizeof($resumen[0]->exploracion_cabeza_cuello)>0 ? $resumen[0]->exploracion_cabeza_cuello[0] : [],
            'exploracion_oftalmologico' => sizeof($resumen[0]->exploracion_oftalmologico)>0 ? $resumen[0]->exploracion_oftalmologico[0] : [],
            'exploracion_respiratorio' => sizeof($resumen[0]->exploracion_respiratorio)>0 ? $resumen[0]->exploracion_respiratorio[0] : [],
            'exploracion_cardiovascular' => sizeof($resumen[0]->exploracion_cardiovascular)>0 ? $resumen[0]->exploracion_cardiovascular[0] : [],
            'exploracion_neurologico' => sizeof($resumen[0]->exploracion_neurologico)>0 ? $resumen[0]->exploracion_neurologico[0] : [],
            'exploracion_ginecoobstetrico' => sizeof($resumen[0]->exploracion_ginecoobstetrico)>0 ? $resumen[0]->exploracion_ginecoobstetrico[0] : [],
            'exploracion_abdomen' => sizeof($resumen[0]->exploracion_abdomen)>0 ? $resumen[0]->exploracion_abdomen[0] : [],
            'diagnostico_sindromaticos' => sizeof($resumen[0]->diagnostico_sindromaticos) > 0 ? $resumen[0]->diagnostico_sindromaticos : [],
            'sindromaticos' =>sizeof($resumen[0]->sindromaticos) > 0 ? $resumen[0]->sindromaticos[0] : [],
            'diagnostico_etiologicos' => sizeof($resumen[0]->diagnostico_etiologicos) > 0 ? $resumen[0]->diagnostico_etiologicos : [],
            'etiologicos' =>sizeof($resumen[0]->etiologicos) > 0 ? $resumen[0]->etiologicos : [],
            'diagnostico_diferenciales' => sizeof($resumen[0]->diagnostico_diferenciales) > 0 ? $resumen[0]->diagnostico_diferenciales : [],
            'diferenciales' =>sizeof($resumen[0]->diferenciales) > 0 ? $resumen[0]->diferenciales : [],
            'otros_estudios' => sizeof($resumen[0]->estudios_padre) > 0 ? $resumen[0]->estudios_padre[0] : [],
            'estudios_laboratorio' => sizeof($resumen[0]->estudios_laboratorio) > 0 ? $resumen[0]->estudios_laboratorio[0] : [],
            'estudios_electrocardiograma' => sizeof($resumen[0]->estudios_electrocardiograma) > 0 ? $resumen[0]->estudios_electrocardiograma[0] : [],
            'estudios_radiografias' => sizeof($resumen[0]->estudios_radiografias) > 0 ? $resumen[0]->estudios_radiografias[0] : [],
            'tratamiento' => sizeof($resumen[0]->tratamiento) > 0 ? $resumen[0]->tratamiento[0] : [],
        ]);
    }

    public function ImportarPDF(Request $request, $id){
       // $fecha_hoy = Carbon::now()->format('Y-m-d');
       $usuario_auth = Auth::user();
    //   if(Session::get('RolUsuario') == 3 or  Session::get('RolUsuario') == 4 ){
        $resumen = Consultas::raw(function ($collection) use ($id) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'PacientesPerfil',
                        'localField' => 'id_paciente',
                        'foreignField' => '_id',
                        'as' => 'paciente',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'DependenciasSS',
                        'localField' => 'id_consultorio',
                        'foreignField' => '_id',
                        'as' => 'consultorio',
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
                    '$lookup' => [
                        'from' => 'ConsultaExploracion',
                        'localField' => '_id',
                        'foreignField' => 'consulta',
                        'as' => 'exploracion',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'ConsultaAntecedentes',
                        'localField' => 'id_paciente',
                        'foreignField' => 'pacienteId',
                        'as' => 'antecedentes',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'UsuariosPerfiles',
                        'localField' => 'medico',
                        'foreignField' => '_id',
                        'as' => 'medico',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'users',
                        'localField' => 'medico.usuarioId',
                        'foreignField' => '_id',
                        'as' => 'user',
                    ],
                ],
                [
                    '$unwind' => [
                        'path'=> '$antecedentes','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$unwind' => '$paciente'
                ],
                [
                    '$unwind' => '$consultorio'
                ],
                [
                    '$unwind' => '$tratamiento'
                ],
                [
                    '$unwind' => '$exploracion'
                ],
                [
                    '$unwind' => '$medico'
                ],
                [
                    '$unwind' => '$user'
                ],
                [
                    '$match' => [
                        '_id' =>  new \MongoDB\BSON\ObjectId($id),
                    ],
                ],
            ]);
        })->first();

        $usuario = UsuariosPerfiles::raw(function ($collection) use ($usuario_auth) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'users',
                        'localField' => 'usuarioId',
                        'foreignField' => '_id',
                        'as' => 'user',
                    ],
                ],
                [
                    '$unwind' => '$user'
                ],
                [
                    '$match' => [
                        'usuarioId' => new ObjectId($usuario_auth->_id),
                    ],
                ],
                [
                    '$project' => [
                        'nombre'=> 1,
                        'primer_apellido' => 1,
                        'segundo_apellido' => 1,
                        //'no_cedula' => 1,
                        //'titulo_obtenido' => 1,
                        'no_cuenta' => 1,
                        'user.rol' => 1
                    ],
                ],           
            ]);
        })->first();

        $contador = $resumen;
        $datos_paciente = $resumen['paciente'];
        $datos_consultorio = $resumen['consultorio'];
        $edad = $resumen['tratamiento'];
        $datos_tratamiento = $resumen['tratamiento']['arreglo_medicamento'];
        $plan_terapeutico = $resumen['tratamiento']['plan_terapeutico'];
        $ordenes_estudios = $resumen['tratamiento']['ordenes_estudios'];
        $exploracion = $resumen['exploracion'];
        $antecedentes = $resumen['antecedentes'];
        $medico_formacion = $resumen['medico'];
        $rol_medico_formacion = $resumen['user'];
        $datos_medico = $usuario;

        $folio = str_pad($contador['contador_folio'], 4, '0', STR_PAD_LEFT);

        $fecha_hoy = date('d-m-Y H:i',strtotime($resumen['created_at']));
 //       Log::debug("Que trae tratamiento", [$ordenes_estudios]);        
        $url = env('APP_URL');
        $cadena_datos = $url.'/qr'.'/'.$contador->id_paciente.'/'.$contador->contador_folio.'/'.$contador->id;
        $qrCode = QrCode::size(60)->generate($cadena_datos);

        $pdf = \PDF::loadView('recetas/PDF_Receta',compact('rol_medico_formacion','medico_formacion','ordenes_estudios','datos_medico','datos_paciente','fecha_hoy','datos_consultorio','antecedentes','edad','exploracion','datos_tratamiento','plan_terapeutico','folio','qrCode'));

        $fileName = 'Receta.pdf';
        return $pdf->download($fileName);
    //    }else{
    //     return response()->json(['message' => 'No tiene permisos para ver esta sección'], 500);
    //    }

    }
}
