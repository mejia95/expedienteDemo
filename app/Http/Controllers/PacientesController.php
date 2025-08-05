<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use App\Models\Pacientes;
use App\Models\UsuariosPerfiles;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Consultas;
use MongoDB\BSON\UTCDateTime;
use App\Http\Repositorios\PacientesRepositorio;
use App\Http\Repositorios\AntecedentesRepositorio;
use MongoDB\BSON\Regex;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Antecedentes;
use Auth;

class PacientesController extends Controller
{
    public function __construct(PacientesRepositorio $pacientes_repositorio, AntecedentesRepositorio $antecedentes_repositorio)
    {
        $this->pacientes_repositorio = $pacientes_repositorio;
        $this->antecedentes_repositorio = $antecedentes_repositorio;
       
    }

    public function GuardarPaciente(Request $request)
    {
        $userId = Auth::user()->id;

        $consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($userId))->first();
        $id_paciente = $request->id_paciente ? $request->id_paciente : null;
      //return  $userId = new ObjectId(Session::get());        
        $datos_usuario = [];
        $NombrePaciente = $request->NombrePaciente;
        $PrimerApellidoPaciente = $request->PrimerApellidoPaciente;
        $SegundoApellidoPaciente = $request->SegundoApellidoPaciente;
        $recien_nacido = $request->recien_nacido;
        $CurpPaciente = $request->CurpPaciente;
        $GeneroPaciente = $request->GeneroPaciente;
        $FechaNacimientoPaciente = $request->FechaNacimientoPaciente;
        $EdadPaciente = $request->EdadPaciente;
        $OcupacionPaciente = $request->OcupacionPaciente;
        $EstadoCivilPaciente = $request->EstadoCivilPaciente;
        $NacionalidadPaciente = $request->NacionalidadPaciente;
        $TipoSangrePaciente = $request->TipoSangrePaciente;
        $CorreoPaciente = $request->CorreoPaciente;
        $TelefonoPaciente = $request->TelefonoPaciente;
        $CelularPaciente = $request->CelularPaciente;

        $validacion = $this->pacientes_repositorio->ValidarDatos($request);
        $exist_paciente = $this->pacientes_repositorio->ValidarPaciente($recien_nacido, $CurpPaciente, $id_paciente) ;
 
        if($exist_paciente==0){
            return response()->json(['mensaje'=>'El paciente ya se encuentra registrado en el sistema.'],422);
        }        

        if(is_int($validacion)){ 
            
            $username = $CorreoPaciente ? explode("@",$CorreoPaciente) :null;
            if($username!=null){
                $username = $username[0];
            }
           
            try{
                $datos_usuario = [
                    'rol'=>6,'activo'=>1,
                    'created_by'=>$userId
                ];

                if($CorreoPaciente){
                    $datos_usuario['email'] = $CorreoPaciente ? trim(strtolower($CorreoPaciente)) : null;
                    $datos_usuario['nameUser'] = $username;
                }
               
                if(!$id_paciente){
                    $nuevoUsuarioPaciente = User::create($datos_usuario);
                   
                    try{
                        $datos_perfil = [
                            "nombre"=>$NombrePaciente,
                            "primer_apellido"=>$PrimerApellidoPaciente,
                            "segundo_apellido"=>$SegundoApellidoPaciente,
                            'recien_nacido'=>$recien_nacido,
                            "CURP"=>trim(strtoupper($CurpPaciente)),
                            "genero"=>$GeneroPaciente,
                            "fecha_nacimiento"=>date("Y-m-d",strtotime($FechaNacimientoPaciente)),
                            "edad"=>$EdadPaciente,
                            "ocupacion"=>$OcupacionPaciente,
                            "estado_civil"=>$EstadoCivilPaciente,
                            "nacionalidad"=>$NacionalidadPaciente,
                            "tipo_sangre"=>$TipoSangrePaciente,
                            "correo"=>trim(strtolower($CorreoPaciente)),
                            "telefono"=>$TelefonoPaciente ? strtoupper($TelefonoPaciente) :null,
                            "telefono_celular"=>$CelularPaciente ? strtoupper($CelularPaciente) : null,
                            "usuario"=> new ObjectId($nuevoUsuarioPaciente->_id),
                            "grupo"=>1,
                            "consultorio" => new ObjectId($consultorio->consultorio),
                            "created_by"=>new ObjectId($userId),                            
                            "created_at"=>new UTCDateTime(Carbon::now()->timestamp * 1000),
                            "updated_at"=>null,
                        ];
          
                            $PerfilPaciente = Pacientes::create($datos_perfil);
                            $request->merge(['id_paciente'=>$PerfilPaciente->_id]);                                            
                            $agregar =  $this->antecedentes_repositorio->Agregar($request);
                                            
                        return response()->json(['message'=>'Se ha registrado un nuevo paciente correctamente']); 
                    }catch(\Throwable $e){
                        if($nuevoUsuarioPaciente){
                            $borrarPaciente = Pacientes::where('usuario',new ObjectId($nuevoUsuarioPaciente->_id))->delete();
                            $borrarUser = User::where('_id',new ObjectId($nuevoUsuarioPaciente->_id))->delete();
                        }
                        return response()->json(['message'=>$e->getMessage()],500);
                    }
                }else if($id_paciente){

                            $datos_perfil_modificar = [
                                "nombre"=>$NombrePaciente,
                                "primer_apellido"=>$PrimerApellidoPaciente,
                                "segundo_apellido"=>$SegundoApellidoPaciente,
                                'recien_nacido'=>$recien_nacido,
                                "CURP"=>trim(strtoupper($CurpPaciente)),
                                "genero"=>$GeneroPaciente,
                                "fecha_nacimiento"=>date("Y-m-d",strtotime($FechaNacimientoPaciente)),
                                "edad"=>$EdadPaciente,
                                "ocupacion"=>$OcupacionPaciente,
                                "estado_civil"=>$EstadoCivilPaciente,
                                "nacionalidad"=>$NacionalidadPaciente,
                                "tipo_sangre"=>$TipoSangrePaciente,
                                "correo"=>trim(strtolower($CorreoPaciente)),
                                "telefono"=>$TelefonoPaciente ? strtoupper($TelefonoPaciente) :null,
                                "telefono_celular"=>$CelularPaciente ? strtoupper($CelularPaciente) : null,
                                "updated_at"=>date("Y-m-d H:i:s"),
                                "updated_by"=>new ObjectId($userId),
                            ];
                    $modificar_paciente = Pacientes::find($id_paciente);
                    $actualizarEstado = User::where('_id',$modificar_paciente->usuario)->update(['updated_by'=>$userId]);
                    $modificar_paciente = $modificar_paciente->update($datos_perfil_modificar);                    
                    $actualizar =  $this->antecedentes_repositorio->Actualizar($id_paciente, $request);

                    return response()->json(['message'=>'Se ha modificado al paciente correctamente']); 
                }
                }catch(\Throwable $e){
                    return response()->json(['message'=>$e->getMessage()],500);
                }
            }else{
                return $validacion;
            }
    }

    public function ObtenerMisPacientes(Request $request)
    {
         try{ 
            $i=0;
           // $userId = new ObjectId(Session::get('IdUsuario'));
            $userId = Auth::user()->id;
            $ordenarPor = $request->ordenarPor ? $request->ordenarPor : 'primer_apellido';
            $orden = $request->orden ? $request->orden : 1; 
            $pagina = $request->pagina ? $request->pagina : 1;       
            
            $limite = 10;
            $offset = ($pagina - 1) * $limite; 
            $condiciones = array();

            $datos_pacientes = [];
            $busqueda = $request->busqueda ? $request->busqueda :null;


            $fechasDesde = $request->FiltroDesde ? date("Y-m-d",strtotime($request->FiltroDesde)):null;
            $fechasHasta = $request->FiltroHasta ? date("Y-m-d",strtotime($request->FiltroHasta)):null;

            $NoConsultasCondicion = $request->NoConsultasCondicion ? $request->NoConsultasCondicion:null;
            $NoConsultas = $request->NoConsultas;
       
            //if(Session::get('RolUsuario')==1 && Session::get('RolUsuario')==6){abort(510);}

            $consulta_consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($userId))->first();
            $pipeline = [
                [
                    '$project'=>['_id'=>1,'nombre'=>1,'primer_apellido'=>1,'segundo_apellido'=>1,'fecha_nacimiento'=>1,'telefono'=>1,'telefono_celular'=>1,'correo'=>1,'genero'=>1,'estado_civil'=>1,'tipo_sangre'=>1,'created_by'=>1,'nacionalidad'=>1,'consultorio'=>1]
                ],
                [
                    '$lookup'=>[
                        'from'=>'Generos',
                        'localField'=>'genero',
                        'foreignField'=>'GeneroValor',
                        'as'=>'GeneroPaciente'
                    ]
                ],
                [
                    '$unwind'=>[
                        'path'=>'$GeneroPaciente','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$lookup'=>[
                        'from'=>'CatEstadoCivil',
                        'localField'=>'estado_civil',
                        'foreignField'=>'valor',
                        'as'=>'EstadoCivilPaciente'
                    ]
                ],
                [
                    '$unwind'=>[
                        'path'=>'$EstadoCivilPaciente','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                 [
                    '$lookup'=>[
                        'from'=>'CatNacionalidades',
                        'localField'=>'nacionalidad',
                        'foreignField'=>'nacionalidadCod',
                        'as'=>'NacionalidadPaciente'
                    ]
                ],
                [
                    '$unwind'=>[
                        'path'=>'$NacionalidadPaciente','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$lookup'=>[
                        'from'=>'CatTipoSangre',
                        'localField'=>'tipo_sangre',
                        'foreignField'=>'valor',
                        'as'=>'TipoSangre'
                    ]
                ],
                [
                    '$unwind'=>[
                        'path'=>'$TipoSangre','preserveNullAndEmptyArrays'=>true
                    ]
                ],
                [
                    '$lookup'=>[
                        'from'=>'Consultas',
                        'localField'=>'_id',
                        'foreignField'=>'id_paciente',
                        'as'=>"consultas",
                        'pipeline'=>[
                            ['$project'=>['estado'=>1,'id_paciente'=>1,'id_consultorio'=>1]],
                            [
                                '$match' => [
                                    'estado' => 2  // Condición para que solo incluya las consultas con estado 2
                                ]
                            ],
                            [
                                '$match' => [
                                    'id_consultorio' => new ObjectId($consulta_consultorio->consultorio)  // Condición para que solo incluya las consultas con estado 2
                                ]
                            ],
                            [
                                '$group'=>[
                                    '_id'=>'id_paciente',
                                    'TotalConsultas'=>[
                                        '$sum'=>1
                                    ],
                                ]
                            ],
                        ]
                    ]
                ],
                [
                    '$unwind'=>['path'=>'$consultas','preserveNullAndEmptyArrays'=>true],

                ],
                [
                    '$addFields' => [
                        'estado_civil'=>'$EstadoCivilPaciente.etiqueta',
                        'nacionalidad'=>'$NacionalidadPaciente.nacionalidadPais',
                        'genero'=>'$GeneroPaciente.GeneroEtiqueta',
                        'tipo_sangre'=>'$TipoSangre.etiqueta',
                        'consultorio'=>'$consultorio',
                        'TotalConsultas'=>[
                            '$ifNull' => ['$consultas.TotalConsultas', 0] 
                        ],
                        'nombreCompleto' => [
                                    '$concat' => [
                                        ['$ifNull'=>['$primer_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$segundo_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$nombre','']],
                                        '',
                                    ]
                        ],
                        'telefonoClear'=>[
                            '$toString'=>[
                                '$arrayElemAt'=>[['$split'=>['$telefono','-']],1]
                            ]
                        ],
                        'telefonoExt'=>[
                            '$toString'=>[
                                '$arrayElemAt'=>[['$split'=>['$telefono','-']],0]
                            ]
                        ],
                        'celularClear'=>[
                            '$toString'=>[
                                '$arrayElemAt'=>[['$split'=>['$telefono_celular','-']],1]
                            ]
                        ],
                        'celularExt'=>[
                            '$toString'=>[
                                '$arrayElemAt'=>[['$split'=>['$telefono_celular','-']],0]
                            ]
                        ],
                    ]
                ],
                [
                    '$match'=>[
                        '$or'=>[
                            ['TotalConsultas'=>['$gt'=>0]],
                            // ['created_by'=>new ObjectId($userId)]
                            ['consultorio'=>new ObjectId($consulta_consultorio->consultorio)]
                            
                        ],
                    ]
                ]
              
            ];
            $pipelineTotal =  $pipeline;

            if($busqueda){
                $condicion  = array('nombreCompleto'=>new Regex($busqueda,'i')) ;
                $condiciones['$match']['$and'] []= $condicion;
                
            }
            //Valida que se ocupen los filtros de busqueda por fecha de nacimiento
            //y se agregan a las condiciones de busqueda
            if($fechasDesde and $fechasHasta){
                $condicion = array('fecha_nacimiento'=>[
                                    '$gte'=>$fechasDesde,'$lte'=>$fechasHasta
                                ]) ;
               
                $condiciones['$match']['$and'] []= $condicion;
            }

            //Filtro de no. consultas
            if($NoConsultasCondicion and $NoConsultas>=0){
                $tipoCondicion= $NoConsultasCondicion == 1 ? '$eq':($NoConsultasCondicion == 2 ? '$gt':'$lt');
                $condicion = array('TotalConsultas'=>[
                                    $tipoCondicion=>intval($NoConsultas)
                                ]) ;
               
               // $condiciones['$match']['$expr'] []= $condicion;
               $condiciones['$match']['$and'] []= $condicion;

            }

            //Si existen condiciones se agregan al pipeline de la consulta
            if($condiciones){
                $pipeline[] = $condiciones;
                $pipelineTotal[] = $condiciones;
                
            }

            $pipelineTotal[] = [
                '$count' => 'total_pacientes'
            ];
            $total_pacientes = Pacientes::raw(function($table) use($pipelineTotal){
                return $table->aggregate($pipelineTotal);
            })->first();
            $TotalPacientes = $total_pacientes ? $total_pacientes->total_pacientes : 0;


            //Agregar el limite de registros por pagina y la pagina de donde se esta obteniendo la informacion. Es importate que primero vaya el skip y luego el limite
            array_push($pipeline,['$sort'=>[$ordenarPor=>(int)$orden]]);
            array_push($pipeline,['$skip'=>$offset],['$limit'=>$limite]);

            $pacientes = Pacientes::raw(function($table) use($pipeline){
                    return $table->aggregate($pipeline);
            });
            
            if(sizeof($pacientes)<1){
                $datos_pacientes['data'] = [];
            }

          $datos_pacientes['total']=intval($TotalPacientes);
           $datos_pacientes['last_page']=ceil ($TotalPacientes / $limite);
           $last_page = ceil ($TotalPacientes / $limite);
        

            foreach ($pacientes as $paciente) {
                $ext_tel = strpos($paciente['telefono'], "-");
                $ext_tel_start = $ext_tel + 1;
                $ext_tel_cel = strpos($paciente['telefono_celular'], "-");
                $ext_tel_cel_start = $ext_tel_cel + 1;

                $total_Consultas = $paciente['TotalConsultas'] ? $paciente['TotalConsultas'] : 0;
                $total_Consultas_clear = $total_Consultas > 99 ? '99+':$total_Consultas;
                $fechaNacimiento = Carbon::parse($paciente['fecha_nacimiento']);
                $edad = $fechaNacimiento->diff(Carbon::now())->format("%y año(s) %m mes(es) %d día(s)");

                $datos_pacientes['data'][]=[
                    "index"=>$pagina * $limite - $limite+1 + $i,
                    "pid"=>$paciente['_id'],
                    'edad'=>$edad,
                    'tipo_sangre'=>$paciente['tipo_sangre'],
                    'estado_civil'=>$paciente['estado_civil'],
                    'nacionalidad'=>$paciente['nacionalidad'],
                    'genero'=>$paciente['genero'],
                    "paciente"=>$paciente['primer_apellido']." ".$paciente['segundo_apellido']." ".$paciente['nombre'],
                    "mail"=>$paciente['correo'],
                    "fecha_nacimiento"=> $paciente['fecha_nacimiento'] ? date_format(date_create($paciente['fecha_nacimiento']),'d-m-Y'): 'N/R',
                    "telefono"=>$paciente['telefonoClear'],
                    "telefono_ext"=>$paciente['telefonoExt'],
                    "telefono_celular"=> $paciente['celularClear'],
                    "telefono_celular_ext"=>$paciente['celularExt'],
                     "consultorio"=>$paciente['consultorio'],
                "consultas"=>$total_Consultas,
                ];
                $i++;
            }
            
            $to = $pagina < $last_page ? sizeof($datos_pacientes['data']) * $pagina : $datos_pacientes['total'];
            $datos_pacientes['to']=intval($to);
            $datos_pacientes['per_page']=$limite;
            $datos_pacientes['pagina']=$pagina;
            
            return $datos_pacientes; 

        }catch(\Throwable $e){
            return $e;
           return response()->json(['message'=>'Ha ocurrido un error al obtener la información de los pacientes.'.$e],500);
        } 
    }

     public function ObtenerPacientes(Request $request)
    {
      $usuario = Auth::user();
        
        try{

            $i=0;

            $ordenarPor = $request->ordenarPor ? $request->ordenarPor : 'primer_apellido';
            $orden = $request->orden ? $request->orden : 1;
            $pagina = $request->pagina ? $request->pagina : 1;

            $limite = 10;
            $offset = ($pagina - 1) * $limite;
            $condiciones = array();

            $datos_pacientes = [];
            $busqueda = $request->busqueda ? $request->busqueda :null;


            $fechasDesde = $request->FiltroDesde ? date("Y-m-d",strtotime($request->FiltroDesde)):null;
            $fechasHasta = $request->FiltroHasta ? date("Y-m-d",strtotime($request->FiltroHasta)):null;

            $NoConsultasCondicion = $request->NoConsultasCondicion ? $request->NoConsultasCondicion:null;
            $NoConsultas = $request->NoConsultas;
           
                    $pipeline = [
                        [
                            '$project'=>['_id'=>1,'nombre'=>1,'primer_apellido'=>1,'segundo_apellido'=>1,'fecha_nacimiento'=>1,'telefono'=>1,'telefono_celular'=>1,'correo'=>1,'genero'=>1,'estado_civil'=>1,'tipo_sangre'=>1, 'recien_nacido'=>1, 'ocupacion'=>1, 'CURP'=>1, 'created_by'=>1]
                        ],
                        [
                            '$lookup'=>[
                                'from'=>'CatGeneros',
                                'localField'=>'genero',
                                'foreignField'=>'GeneroValor',
                                'as'=>'GeneroPaciente'
                            ]
                        ],
                        [
                            '$unwind'=>[
                                'path'=>'$GeneroPaciente','preserveNullAndEmptyArrays'=>true
                            ]
                        ],

                        [
                            '$lookup'=>[
                                'from'=>'CatEstadoCivil',
                                'localField'=>'estado_civil',
                                'foreignField'=>'valor',
                                'as'=>'EstadoCivilPaciente'
                            ]
                        ],
                        [
                            '$unwind'=>[
                                'path'=>'$EstadoCivilPaciente','preserveNullAndEmptyArrays'=>true
                            ]
                        ],
                        [
                            '$lookup'=>[
                                'from'=>'CatTipoSangre',
                                'localField'=>'tipo_sangre',
                                'foreignField'=>'valor',
                                'as'=>'TipoSangre'
                            ]
                        ],
                        [
                            '$unwind'=>[
                                'path'=>'$TipoSangre','preserveNullAndEmptyArrays'=>true
                            ]
                        ],
                        [
                            '$lookup'=>[
                                'from'=>'Consultas',
                                'localField'=>'_id',
                                'foreignField'=>'id_paciente',
                                'as'=>"consultas",
                                'pipeline'=>[
                                    [
                                        '$match' => [
                                            'estado' => 2  // Condición para que solo incluya las consultas con estado 2
                                        ]
                                    ],
                                    [
                                        '$group'=>[
                                            '_id'=>'id_paciente',
                                            'TotalConsultas'=>[
                                                '$sum'=>1
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$unwind'=>['path'=>'$consultas','preserveNullAndEmptyArrays'=>true],

                        ],
                        [
                            '$addFields' => [
                               'estado_civil'=>'$EstadoCivilPaciente.etiqueta',
                                'genero'=>'$GeneroPaciente.GeneroEtiqueta',
                                'tipo_sangre'=>'$TipoSangre.etiqueta',
                                'TotalConsultas'=>'$consultas.TotalConsultas',
                                'nombreCompleto' => [
                                    '$concat' => [
                                        ['$ifNull'=>['$primer_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$segundo_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$nombre','']],
                                        '',
                                    ]
                                ],
                                'telefonoClear' => [
                                    '$toString' => [
                                        '$arrayElemAt' => [
                                            ['$split' => ['$telefono', '-']], 1
                                        ]
                                    ]
                                ],
                                'telefonoExt' => [
                                    '$toString' => [
                                        '$arrayElemAt' => [
                                            ['$split' => ['$telefono', '-']], 0
                                        ]
                                    ]
                                ],
                                'celularClear' => [
                                    '$toString' => [
                                        '$arrayElemAt' => [
                                            ['$split' => ['$telefono_celular', '-']], 1
                                        ]
                                    ]
                                ],
                                'celularExt' => [
                                    '$toString' => [
                                        '$arrayElemAt' => [
                                            ['$split' => ['$telefono_celular', '-']], 0
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ];
                    $pipelineTotal = [
                        [
                            '$project'=>['_id'=>1,'nombre'=>1,'primer_apellido'=>1,'segundo_apellido'=>1,'fecha_nacimiento'=>1,'telefono'=>1,'telefono_celular'=>1,'correo'=>1, 'recien_nacido' => 1]
                        ],
                        [
                            '$lookup'=>[
                                'from'=>'Consultas',
                                'localField'=>'_id',
                                'foreignField'=>'id_paciente',
                                'as'=>"consultas",
                                'pipeline'=>[
                                    [
                                        '$match' => [
                                            'estado' => 2  // Condición para que solo incluya las consultas con estado 2
                                        ],

                                    ],
                                    [
                                        '$group'=>[
                                            '_id'=>'id_paciente',
                                            'TotalConsultas'=>[
                                                '$sum'=>1
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$unwind'=>['path'=>'$consultas','preserveNullAndEmptyArrays'=>true],

                        ],
                        [
                            '$addFields' => [
                                'TotalConsultas'=>'$consultas.TotalConsultas',
                                'nombreCompleto' => [
                                    '$concat' => [
                                        ['$ifNull'=>['$primer_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$segundo_apellido','']],
                                        ' ',
                                        ['$ifNull'=>['$nombre','']],
                                        '',
                                    ]
                                ],
                            ]
                        ],
                    ];
               
           
            
            //Valida que se ocupen los filtros de busqueda por nombre
            // y se agregan a las condiciones de busqueda
            if($busqueda){
                $condicion  = array('nombreCompleto'=>new Regex($busqueda,'i')) ;
                $condiciones['$match']['$and'] []= $condicion;

            }
            //Valida que se ocupen los filtros de busqueda por fecha de nacimiento
            // y se agregan a las condiciones de busqueda
            if($fechasDesde and $fechasHasta){
                $condicion = array('fecha_nacimiento'=>[
                                    '$gte'=>$fechasDesde,'$lte'=>$fechasHasta
                                ]) ;

                $condiciones['$match']['$and'] []= $condicion;
            }

            //Filtro de no. consultas
            if($NoConsultasCondicion and $NoConsultas>=0){
                $tipoCondicion= $NoConsultasCondicion == 1 ? '$eq':($NoConsultasCondicion == 2 ? '$gt':'$lt');
                $condicion = array('TotalConsultas'=>[
                                    $tipoCondicion=>intval($NoConsultas)
                                ]) ;

               // $condiciones['$match']['$expr'] []= $condicion;
               $condiciones['$match']['$and'] []= $condicion;

            }

            
            //Si existen condiciones se agregan al pipeline de la consulta
            if($condiciones){
                $pipeline[] = $condiciones;
                $pipelineTotal[] = $condiciones;

            }
            $pipelineTotal[] = [
                    '$count' => 'total_pacientes'
                ];
           // return $pipelineTotal;

            //Calcula el total de registros con los filtros(si existen)
            $total_pacientes = Pacientes::raw(function($table) use($pipelineTotal){
                return $table->aggregate($pipelineTotal);
            })->first();
            $TotalPacientes = $total_pacientes ? $total_pacientes->total_pacientes : 0;


            //Agregar el limite de registros por pagina y la pagina de donde se esta obteniendo la informacion. Es importate que primero vaya el skip y luego el limite
            array_push($pipeline,['$sort'=>[$ordenarPor=>(int)$orden]]);
            array_push($pipeline,['$skip'=>$offset],['$limit'=>$limite]);
            $pacientes = Pacientes::raw(function($table) use($pipeline){
                    return $table->aggregate($pipeline);
            });

             if(sizeof($pacientes)<1){
                $datos_pacientes['data'] = [];
             }

            $datos_pacientes['total']=intval($TotalPacientes);
            $datos_pacientes['last_page']=ceil ($TotalPacientes / $limite);
            $last_page = ceil ($TotalPacientes / $limite);


            foreach ($pacientes as $paciente) {
                $ext_tel = strpos($paciente['telefono'], "-");
                $ext_tel_start = $ext_tel + 1;
                $ext_tel_cel = strpos($paciente['telefono_celular'], "-");
                $ext_tel_cel_start = $ext_tel_cel + 1;

                $total_Consultas = $paciente['TotalConsultas'] ? $paciente['TotalConsultas'] : 0;
                $total_Consultas_clear = $total_Consultas > 99 ? '99+':$total_Consultas;

                $fechaNacimiento = Carbon::parse($paciente['fecha_nacimiento']);

                $edad = $fechaNacimiento->diff(Carbon::now())->format("%y año(s) %m mes(es) %d día(s)");



                $datos_pacientes['data'][]=[
                    "index"=>$pagina * $limite - $limite+1 + $i,
                    "pid"=>$paciente['_id'],
                    'edad'=>$edad,
                    'tipo_sangre'=>$paciente['tipo_sangre'],
                    'estado_civil'=>$paciente['estado_civil'],
                    'genero'=>$paciente['genero'],
                    "paciente"=>$paciente['primer_apellido']." ".$paciente['segundo_apellido']." ".$paciente['nombre'],
                    "mail"=>$paciente['correo'],
                    "fecha_nacimiento"=> $paciente['fecha_nacimiento'] ? date_format(date_create($paciente['fecha_nacimiento']),'d-m-Y'): 'N/R',
                    "telefono"=>$paciente['telefonoClear'],
                    "telefono_ext"=>$paciente['telefonoExt'],
                    "telefono_celular"=> $paciente['celularClear'],
                    "telefono_celular_ext"=>$paciente['celularExt'],
                    "recien_nacido"=>$paciente['recien_nacido'],
                   "consultas"=>$total_Consultas,
                ];
                $i++;
            }

            $to = $pagina < $last_page ? sizeof($datos_pacientes['data']) * $pagina : $datos_pacientes['total'];
            $datos_pacientes['to']=intval($to);
            $datos_pacientes['per_page']=$limite;
            $datos_pacientes['pagina']=$pagina;
            return $datos_pacientes;
        }catch(\Throwable $error){
           return $error;
        }
    }

    public function ObtenerAntecedentes(Request $request, $id)
    {
    
        $consulta = Antecedentes::raw(function ($collection) use ($id) {
            return $collection->aggregate([
               [
                    '$lookup' => [
                        'from' => 'Toxicomanias',
                        'localField' => 'toxicomanias',
                        'foreignField' => 'valor',
                        'as' => 'ToxicomaniasPaciente',
                    ],
                ],
                [
                    '$lookup' => [
                        'from' => 'CatAntecedentesPerPatologicos',
                        'localField' => 'antecedentesPerPatologicos',
                        'foreignField' => 'eID',
                        'as' => 'antecedentesPerPatologicosPaciente',
                    ],
                ],
                [
                    '$match' => [
                        //'id_consulta' =>  new ObjectId($request->id_consulta),
                        'id_paciente' =>  new ObjectId($id),
                    ],
                ],


            ]);
        });

            return $consulta[0];


    }

    public function ObtenerPerfilPaciente($paciente)
    {
        //
        try{
            $perfilPacienteDatos = [];
            $pipelinePaciente =
                [
                        [
                            '$project'=>['_id'=>1,'nombre'=>1,'primer_apellido'=>1,'segundo_apellido'=>1,'consultas'=>1,'fecha_nacimiento'=>1,'correo'=>1,'CURP'=>1,'genero'=>1,'edad'=>1,'ocupacion'=>1,'estado_civil'=>1,'tipo_sangre'=>1,'telefono'=>1,
                                'usuario'=>1,
                                'telefono_celular'=>1,'recien_nacido'=>1,'nacionalidad'=>1]
                        ],
                        [
                            '$lookup'=>[
                                'from'=>'users',
                                'localField'=>'usuario',
                                'foreignField'=>'_id',
                                'as'=>'Usuario',

                            ]
                        ],
                        [
                            '$addFields' => [
                                'estadoPaciente'=>[
                                    '$toString'=>[
                                        '$arrayElemAt'=>['$Usuario.activo',0]
                                    ]
                                ]
                                ,
                                'telefonoClear'=>[
                                    '$toString'=>[
                                        '$arrayElemAt'=>[['$split'=>['$telefono','-']],1]
                                    ]
                                ],
                                'telefonoExt'=>[
                                    '$toString'=>[
                                        '$arrayElemAt'=>[['$split'=>['$telefono','-']],0]
                                    ]
                                ],
                                'celularClear'=>[
                                    '$toString'=>[
                                        '$arrayElemAt'=>[['$split'=>['$telefono_celular','-']],1]
                                    ]
                                ],
                                'celularExt'=>[
                                    '$toString'=>[
                                        '$arrayElemAt'=>[['$split'=>['$telefono_celular','-']],0]
                                    ]
                                ],
                            ]
                        ],
                        [
                            '$match'=>[
                                '_id'=>new ObjectId($paciente)
                            ]
                        ]
                ];
            $perfilPaciente = Pacientes::raw(function($collection) use($pipelinePaciente){
                    return $collection->aggregate($pipelinePaciente);
                })->first();
            $perfilPacienteDatos = [
                '_id'=>$perfilPaciente->_id,
                'estado'=>$perfilPaciente->estadoPaciente,
                'nombre'=>$perfilPaciente->nombre,
                'primer_apellido'=>$perfilPaciente->primer_apellido,
                'segundo_apellido'=>$perfilPaciente->segundo_apellido,
                'fecha_nacimiento'=>$perfilPaciente->fecha_nacimiento,
                'correo'=>$perfilPaciente->correo,
                'CURP'=>$perfilPaciente->CURP,
                'genero'=>$perfilPaciente->genero,
                'nacionalidad'=>$perfilPaciente->nacionalidad,
                'edad'=>$perfilPaciente->edad,
                'ocupacion'=>$perfilPaciente->ocupacion,
                'estado_civil'=>$perfilPaciente->estado_civil,
                'tipo_sangre'=>$perfilPaciente->tipo_sangre,
                'recien_nacido'=>$perfilPaciente->recien_nacido,
                'telefonoClear'=>$perfilPaciente->telefonoClear,
                'telefonoExt'=>$perfilPaciente->telefonoExt ? $perfilPaciente->telefonoExt:'MX' ,
                'celularClear'=>$perfilPaciente->celularClear,
                'celularExt'=>$perfilPaciente->celularExt ? $perfilPaciente->celularExt:'MX'
            ];
            return response()->json($perfilPacienteDatos);
        }catch(\Throwable $error){
            abort(500);
        }

    }

    public function ActualizarAntecedentes(Request $request){

        $id_consulta = $request->id_consulta;

        $actualizar =  $this->antecedentes_repositorio->Actualizar($id_consulta, $request);

        return $actualizar;
    }

    public function ObtenerHistorialConsultas($paciente,Request $request){
        if(!$paciente){abort(500);}
        $datos_paciente = [];
        $pagina = $request->pagina ? $request->pagina:1;
        $ordenarPor = $request->ordenarPor ? $request->ordenarPor : '_id';
        $orden = $request->orden ? $request->orden : 1;
        $busqueda = $request->busqueda ? $request->busqueda :null;
        $ConsultoriosFiltros = $request->ConsultoriosFiltros ? $request->ConsultoriosFiltros : null;
        $MedicosFiltros = $request->MedicosFiltros ? $request->MedicosFiltros : null;
        $FechaDesdeFiltro = $request->FechaDesdeFiltro ? date("d-m-Y",strtotime($request->FechaDesdeFiltro)):null;
        $FechaHastaFiltro = $request->FechaHastaFiltro ? date("d-m-Y",strtotime($request->FechaHastaFiltro)):date("d-m-Y");

        $limite = 10;
        $offset = ($pagina - 1) * $limite;
        $datos_consulta = [];
        $i=0;
        $condiciones = array();

        $usuario = Auth::user();

        $condicionPasante=['id_paciente'=>new ObjectId($paciente),'estado'=>2];
        if($usuario->rol != 1 && $usuario->rol != 6){
             $medico = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->_id))->first();
            if($usuario->rol == 3){
                $condicionPasante=['id_consultorio'=>new ObjectId($medico->consultorio),'id_paciente'=>new ObjectId($paciente),'estado'=>2];
            }

        }

        //'id_paciente'=>new ObjectId($paciente),
        //'estado'=>2,

        $pipelineHistorialConsultas = [
            [
                '$project'=>['_id'=>1,'id_paciente'=>1,'id_consultorio'=>1,'created_at'=>1,'medico'=>1,'estado'=>1]
            ],[
                '$lookup'=>[
                    'from'=>'DependenciasSS',
                    'localField'=>'id_consultorio',
                    'foreignField'=>'_id',
                    'as'=>'Consultorio'
                ]
            ],[
                '$lookup'=>[
                    'from'=>'UsuariosPerfiles',
                    'localField'=>'medico',
                    'foreignField'=>'_id',
                    'as'=>'Medico',
                    'pipeline'=>[
                        [
                            '$addFields'=>[
                                'nombreCompleto'=>[
                                    '$concat'=>['$nombre',' ','$primer_apellido',' ','$segundo_apellido']
                                ],
                                'cedula'=>'$no_cedula',
                                'cuenta'=>'$no_cuenta_mpss',

                            ]
                        ]
                    ]
                ]
            ],
            [
                '$addFields'=>[
                    'idConsulta'=>[
                        '$toString'=>'$_id'
                    ],
                    'fecha_creacion'=>[
                        '$dateToString'=>[
                            'format'=>"%d-%m-%Y %H:%M:%S",
                            'date'=>'$created_at',
                            'timezone'=> 'America/Mexico_City'
                        ]
                    ],
                    'fecha_creacion_clear'=>[
                        '$dateToString'=>[
                            'format'=>"%d-%m-%Y",
                            'date'=>'$created_at'
                        ]
                    ],
                    'consultorio'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Consultorio.nombre',0]
                        ]
                    ],
                    'no_cc'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Consultorio.no_cc',0]
                        ]
                    ],
                    'idMedico'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Medico._id',0]
                        ]
                    ],'NombreMedico'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Medico.nombreCompleto',0]
                        ]
                    ],'medico_cedula'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Medico.cedula',0]
                        ]
                    ],'medico_cuenta'=>[
                        '$toString'=>[
                            '$arrayElemAt'=>['$Medico.cuenta',0]
                        ]
                    ],
                    'medico_titulado'=>[

                            '$arrayElemAt'=>['$Medico.medico_titulado',0]

                    ],
                ]
            ],
            [
                '$project'=>['idConsulta'=>1,'fecha_creacion_clear'=>1,'idMedico'=>1,'medico_titulado'=> 1,'id_consultorio'=>1,'Consultorio'=>1,'Medico'=>1,'created_at'=>1,'id_paciente'=>1,'estado'=>1,'NombreMedico'=>1,'medico_cedula'=>1,'medico_cuenta'=>1,'consultorio'=>1,'cuenta'=>1,'fecha_creacion'=>1,'no_cc'=>1]
            ],
            [
                '$match' =>$condicionPasante,
            ],
        ];

        if($usuario->rol==3){
             $consulta_consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->_id))->first();            
                $condicion  = array('id_consultorio'=>new ObjectId($consulta_consultorio->consultorio)) ;
                $condiciones['$match']['$and'] []= $condicion;            
        }
        if($busqueda){
                $condicion  = array('idConsulta'=>new Regex($busqueda,'i')) ;
                $condiciones['$match']['$and'] []= $condicion;

        }

        if($FechaDesdeFiltro){

            $condicion = array('fecha_creacion_clear'=>[
                                '$gte'=>$FechaDesdeFiltro,'$lte'=>$FechaHastaFiltro
                            ]) ;

            $condiciones['$match']['$and'] []= $condicion;

        }

        if($ConsultoriosFiltros){
            $condicion = array('no_cc'=>[
                '$in'=>$ConsultoriosFiltros
            ]) ;
            $condiciones['$match']['$and'] []= $condicion;
        }
        if($MedicosFiltros){
            $condicion = array('idMedico'=>[
                '$in'=>$MedicosFiltros
            ]) ;
            $condiciones['$match']['$and'] []= $condicion;
        }

        if($condiciones){
                $pipelineHistorialConsultas[] = $condiciones;
        }





        $TotalListaConsultas = Consultas::raw(function($collection)use($pipelineHistorialConsultas){
            return $collection->aggregate($pipelineHistorialConsultas);
        })->count();
        array_push($pipelineHistorialConsultas,['$sort'=>[$ordenarPor=>(int)$orden]]);
        array_push($pipelineHistorialConsultas,['$skip'=>$offset],['$limit'=>$limite]);
        $datos_consultas['total']=number_format($TotalListaConsultas);
        $datos_consultas['last_page']=ceil ($TotalListaConsultas / $limite);
        $last_page = ceil ($TotalListaConsultas / $limite);

        $ListaConsultas = Consultas::raw(function($collection)use($pipelineHistorialConsultas){
            return $collection->aggregate($pipelineHistorialConsultas);
        });

        foreach ($ListaConsultas as $consulta) {
                $datos_consultas['data'][]=[
                    "index"=>$pagina * $limite - $limite+1 + $i,
                    "_id"=>$consulta->_id,
                    "fecha_creacion"=>$consulta->fecha_creacion,
                    "consultorio"=>$consulta->consultorio,
                    "NombreMedico"=>$consulta->NombreMedico,
                    "medico_cedula"=>$consulta->medico_cedula,
                    "medico_cuenta"=>$consulta->no_cuenta,
                    "medico_titulado"=>$consulta->medico_titulado
                ];
                $i++;
            }

        $to = $pagina < $last_page ? sizeof($datos_consultas['data']) * $pagina : $datos_consultas['total'];

        $datos_consultas['to']=number_format($to);
        $datos_consultas['per_page']=$limite;
        $datos_consultas['pagina']=$pagina;

        $paciente = Pacientes::where('_id',new ObjectId($paciente))->first();
        if($paciente){
            $fechaNacimiento = Carbon::parse($paciente->fecha_nacimiento);

            $edad = $fechaNacimiento->diff(Carbon::now())->format("%y año(s) %m mes(es) %d día(s)");
            $datos_paciente = [
                'nombre'=>"$paciente->nombre $paciente->primer_apellido $paciente->segundo_apellido",
                'paciente'=>$paciente->_id,
                'edad'=>$edad
            ];
        }
        return response()->json(['consulta'=>$datos_consultas,'paciente'=>$datos_paciente]);
    }

    public function VerificarCurp(Request $request){
        $id_paciente = $request->id_paciente;
        $CurpPaciente = $request->CurpPaciente;
        $recien_nacido = $request->recien_nacido;
        
        $validator = Validator::make($request->all(), [       
            'CurpPaciente' => ['bail','required','string','max:18',$request->recien_nacido ? 'string' :'regex:/^[A-Z]{4}[0-9]{6}[A-Z]{6}[A-Z,0-9]{2}$/'],
        ],[
            'CurpPaciente.required' => '<p>El campo <strong>"CURP"</strong> es obligatorio.</p>',
            'CurpPaciente.regex' => '<p>El campo <strong>"CURP"</strong> debe tener un formato válido.</p>',
        ]);     
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            $mensaje = $errors->first();
            return response()->json(['mensaje'=>$mensaje],500);
        }

        $exist_paciente = $this->pacientes_repositorio->ValidarPaciente($recien_nacido, $CurpPaciente, $id_paciente);
        if($exist_paciente){
            return response()->json(['estatus'=>$exist_paciente,'mensaje'=>"No se ha encontrado información en el sistema, puede seguir con el proceso del registro"]);
        }else{
            return response()->json(['estatus'=>$exist_paciente,'mensaje'=>"Este usuario ya está registrado en otra sede. ¿Desea vincularlo como paciente en esta sede?"]);
        }
    }

    public function VincularSede(Request $request){
        $usuario = Auth::user();
        $consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->id))->first();
        try{
            $paciente = Pacientes::where('CURP',$request->CurpPaciente)->first();
            $paciente->consultorio = new ObjectId($consultorio->consultorio);
            $paciente->updated_at = date("Y-m-d H:i:s");
            $paciente->updated_by = new ObjectId($usuario->id);
            $paciente->save();
            if($paciente){
                return response()->json(['mensaje'=>"El usuario ha sido vinculado correctamente.",'datos'=>$paciente]);
            }
        }catch(\Throwable $error){
            abort(500);
        }

    }
}
