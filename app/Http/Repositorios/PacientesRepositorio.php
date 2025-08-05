<?php 

namespace App\Http\Repositorios;
use App\Models\Pacientes;
use Illuminate\Support\Facades\Validator;
use DB;
use Log;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use Carbon\Carbon;
class PacientesRepositorio 
{

    public $hoy,$pipeline_pacientes;
    private $pagina, $limite, $offset, $totalCampos;
    public function __construct() {
        $this->hoy = new UTCDateTime(now());
        $this->pipeline_pacientes = [
            [
                '$project'=>['_id'=>1,
                    
                    'edadC' => [
                        '$floor' => [
                            '$divide' => [
                                [
                                    '$subtract' => [
                                        $this->hoy,
                                        [ '$toDate' => '$fecha_nacimiento' ]
                                    ]
                                ],
                                1000 * 60 * 60 * 24 * 365
                            ]
                        ]
                    ],
                    'nombre'=>1,
                    'primer_apellido'=>1,
                    'segundo_apellido'=>1,
                    'fecha_nacimiento'=>1,
                    'telefono'=>1,
                    'telefono_celular'=>1,
                    'correo'=>1,
                    'genero'=>1,
                    'estado_civil'=>1,
                    'tipo_sangre'=>1,
                    'created_by'=>1,
                    'updated_at'=>1,
                    'created_at'=>1,
                    'nacionalidad'=>1,
                    'consultorio'=>1]
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
                    'from'=>'DependenciasSS',
                    'localField'=>'consultorio',
                    'foreignField'=>'_id',
                    'as'=>'ConsultorioPaciente'
                ]
            ],
            [
                '$unwind'=>[
                    'path'=>'$ConsultorioPaciente','preserveNullAndEmptyArrays'=>true
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
                    'consultorio'=>'$ConsultorioPaciente.nombre',
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
                                    
                                ]
                    ],
                    'pId'=>[
                        '$toString'=>'$_id'
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
                    'act_reciente'=>[
                        '$dateToString' => [
                            'format' => "%Y-%m-%d %H:%M",
                            'date' => ['$ifNull'=>['$updated_at','$created_at']],
                            'timezone' => 'America/Mexico_City'
                        ]
                    ],
                    'created'=>[
                        '$dateToString' => [
                            'format' => "%Y-%m-%d %H:%M",
                            'date' => '$created_at',
                            'timezone' => 'America/Mexico_City'
                        ]
                    ],
                    'updated'=>[
                        '$dateToString' => [
                            'format' => "%Y-%m-%d %H:%M",
                            'date' => '$updated_at',
                            'timezone' => 'America/Mexico_City'
                        ]
                    ]
                ]
            ]
          
        ];
    }

    public function UltimosPacientesCreados(){
        $pipeline = $this->pipeline_pacientes;
        $i=0;
        $hoy = now();
        $this->pagina=1;
        $datos_pacientes = [];
        $this->limite = 5;
        $pipeline[] = ['$match' => ['created_at' => ['$lte' => new \MongoDB\BSON\UTCDateTime()]]];
        $pipeline[] = ['$sort' => ['created_at' => -1]];
        $pipeline[] = ['$limit' => $this->limite];
        $pacientes = DB::table('PacientesPerfil')->raw(function($coleccion) use ($pipeline){
            return $coleccion->aggregate($pipeline);
        })->toArray();

        $this->totalCampos = sizeof($pacientes);


        return $this->obtenerInformacion($pacientes);

        
    }
    public function UltimosPacientesModificados(){
        $pipeline = $this->pipeline_pacientes;
        $i=0;
        $this->pagina=1;
        $datos_pacientes = [];
        $this->limite = 5;
        $pipeline[] = ['$match' => ['updated_at' => ['$lte' => new \MongoDB\BSON\UTCDateTime()]]];
        $pipeline[] = ['$sort' => ['updated_at' => -1]];
        $pipeline[] = ['$limit' => $this->limite];
        $pacientes = DB::table('PacientesPerfil')->raw(function($coleccion) use ($pipeline){
            return $coleccion->aggregate($pipeline);
        })->toArray();

        $this->totalCampos = sizeof($pacientes);

        return $this->obtenerInformacion($pacientes); 
    }


    public function InformacionPacientes ($request){
        
            $i=0;
            $busqueda = $request->busqueda ?? null;
            $datos_pacientes = [];
            $pipeline = $this->pipeline_pacientes;
            $this->pagina = $request->page ?? 1;
            $this->limite = 10;
            $this->offset = ($this->pagina - 1) * $this->limite; 
            $condiciones = array();
    
            $filtroEdadMin = $request->edadMin ?? null;
            $filtroEdadMax = $request->edadMax ?? null;
            $filtroConsultorio = $request->consultorio ?? null;
            $filtroSexo = $request->sexo ?? null;
            if($busqueda){
                $condiciones['$match']['$or'] = [
                    ['nombreCompleto'=>new Regex($busqueda,'i')],
                    ['pId'=>new Regex($busqueda,'i')],
                    ['genero'=>new Regex($busqueda,'i')],
                    ['consultorio'=>new Regex($busqueda,'i')],
                ];
            }
            if(isset($request->fechaInicio)){
                $condiciones['$match']['act_reciente'] = [
                    '$gte'=>$request->fechaInicio,
                ];
            }
            if(isset($request->fechaFin)){
                $condiciones['$match']['act_reciente'] = [
                    '$lte'=>$request->fechaFin
                ];
            }
            if(isset($filtroEdadMin)){
                $condicion = array('edadC'=>[
                    '$gte'=>(int)$filtroEdadMin,
                ]) ;
                $condiciones['$match']['$and'] []= $condicion;
            }
            if(isset($filtroEdadMax)){
                $condicion = array('edadC'=>[
                    '$lte'=>(int)$filtroEdadMax
                    
                ]) ;
                $condiciones['$match']['$and'] []= $condicion;
            }

            if($filtroConsultorio){
                $condicion = array('consultorio'=>[
                    '$in'=>$filtroConsultorio
                ]) ;
                $condiciones['$match']['$and'] []= $condicion;
            }
            if($filtroSexo){
                $condicion = array('genero'=>[
                    '$in'=>$filtroSexo
                ]) ;
                $condiciones['$match']['$and'] []= $condicion;
            }
    
            if($condiciones){
                $pipeline[] = $condiciones;
            }
    
            $total_pacientes = DB::table('PacientesPerfil')->raw(function($coleccion) use ($pipeline){
                return $coleccion->aggregate($pipeline);
            })->toArray();
            //$TotalPacientes = sizeof($total_pacientes);
            $this->totalCampos = sizeof($total_pacientes);
    
    
            array_push($pipeline,['$sort'=>['act_reciente'=>-1]]);
            array_push($pipeline,['$skip'=>$this->offset],['$limit'=>$this->limite]);
    
            $pacientes = DB::table('PacientesPerfil')->raw(function($coleccion) use ($pipeline){
                return $coleccion->aggregate($pipeline);
            })->toArray();
    
            return $this->obtenerInformacion($pacientes);
    
        
            
        
       
    }

    public function obtenerInformacion ($pacientes){
        $datos_pacientes=[];
        $i=0;
        if(sizeof($pacientes)<1){
            $datos_pacientes['data'] = [];
        }
        $pagina = $this->pagina;
        $limite = $this->limite;
        $datos_pacientes['total']=intval($this->totalCampos);
        $datos_pacientes['last_page']=ceil ($this->totalCampos / $this->limite);
        $last_page = ceil ($this->totalCampos / $this->limite);

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
                'edadC'=>$paciente['edadC'],
                'tipo_sangre'=>$paciente['tipo_sangre'] ?? null,
                'estado_civil'=>$paciente['estado_civil'],
                'nacionalidad'=>$paciente['nacionalidad'],
                'genero'=>$paciente['genero'],
                "paciente"=>$paciente['nombreCompleto'],
                "mail"=>$paciente['correo'],
                "fecha_nacimiento"=> $paciente['fecha_nacimiento'] ? date_format(date_create($paciente['fecha_nacimiento']),'d-m-Y'): 'N/R',
                "telefono"=>$paciente['telefonoClear'],
                "telefono_ext"=>$paciente['telefonoExt'],
                "telefono_celular"=> $paciente['celularClear'],
                "telefono_celular_ext"=>$paciente['celularExt'],
                "consultorio"=>$paciente['consultorio'],
                "act_reciente"=>$paciente['act_reciente'],
                "created"=>$paciente['created'],
                "updated"=>$paciente['updated'],
            "consultas"=>$total_Consultas,
            ];
            $i++;
        }
            
        $to = $pagina < $last_page ? sizeof($datos_pacientes['data']) * $pagina : $datos_pacientes['total'];
        $datos_pacientes['to']=intval($to);
        $datos_pacientes['per_page']=$limite;
        $datos_pacientes['pagina']=$pagina; 
        return $datos_pacientes;
    }


    public function ValidarDatos($request){
        
        $validator = Validator::make($request->all(), [
            'NombrePaciente' => 'bail|required|string|max:100|regex:/^[a-zA-ZÁ-ÿñÑ\s]+$/',
            'PrimerApellidoPaciente' => 'bail|required|string|max:100|regex:/^[a-zA-ZÁ-ÿñÑ\s]+$/',
            'SegundoApellidoPaciente' => 'bail|nullable|string|max:100|regex:/^[a-zA-ZÁ-ÿñÑ\s]+$/',
            'anio_nacimiento' => 'bail|required|integer|min:1900|max:'.date('Y'),
            'mes_nacimiento' => 'bail|required|integer|min:1|max:12',
            'dia_nacimiento' => 'bail|required|integer|min:1|max:31',
            // 'FechaNacimientoPaciente' => 'bail|required|date_format:Y-m-d|before_or_equal:'.date('Y-m-d'),
            'FechaNacimientoPaciente' => 'bail|required',
            'recien_nacido' => 'bail|required|boolean',
            'CurpPaciente' => ['bail','required','string','max:18',$request->recien_nacido ? 'string' :'regex:/^[A-Z]{4}[0-9]{6}[A-Z]{6}[A-Z,0-9]{2}$/'],
            'GeneroPaciente' => 'bail|required',            
            'EstadoCivilPaciente' => 'bail|required',
            'NacionalidadPaciente' => 'bail|required',
            'EdadPaciente' => 'bail|required|string',
            'OcupacionPaciente' => 'bail|required|string|max:100|regex:/^[a-zA-ZÁ-ÿñÑ\s]+$/',               
            'CorreoPaciente' => 'bail|nullable|email|max:100',
            'TelefonoPaciente' => 'bail|nullable|string|max:15|regex:/^\d{10}$/',
            'CelularPaciente' => 'bail|nullable|string|max:15|regex:/^\d{10}$/',
        ],[
            'NombrePaciente.required' => '<p>El campo <strong>"Nombre"</strong> es obligatorio.</p>',
            'NombrePaciente.regex' => '<p>El campo <strong>"Nombre"</strong> solo puede contener letras y espacios.</p>',
            'PrimerApellidoPaciente.required' => '<p>El campo <strong>"Primer apellido"</strong> es obligatorio.</p>',
            'PrimerApellidoPaciente.regex' => '<p>El campo <strong>"Primer apellido"</strong> solo puede contener letras y espacios.</p>',
            'SegundoApellidoPaciente.required' => '<p>El campo <strong>"Segundo apellido"</strong> es obligatorio.</p>',
            'SegundoApellidoPaciente.regex' => '<p>El campo <strong>"Segundo apellido"</strong> solo puede contener letras y espacios.</p>',
            'anio_nacimiento.required' => '<p>El campo <strong>"Año de nacimiento"</strong> es obligatorio.</p>',
            'mes_nacimiento.required' => '<p>El campo <strong>"Mes de nacimiento"</strong> es obligatorio.</p>',
            'dia_nacimiento.required' => '<p>El campo <strong>"Día de nacimiento"</strong> es obligatorio.</p>',
            'FechaNacimientoPaciente.required' => '<p>El campo <strong>"Fecha de nacimiento"</strong> es obligatorio.</p>',
            'recien_nacido.required' => '<p>El campo <strong>"Recien nacido"</strong> es obligatorio.</p>',
            'CurpPaciente.required' => '<p>El campo <strong>"CURP"</strong> es obligatorio.</p>',
            'CurpPaciente.regex' => '<p>El campo <strong>"CURP"</strong> debe tener un formato válido.</p>',
            'GeneroPaciente.required' => '<p>El campo <strong>"Género"</strong> es obligatorio.</p>',            
            'EstadoCivilPaciente.required' => '<p>El campo <strong>"Estado civil"</strong> es obligatorio.</p>',            
            'NacionalidadPaciente.required' => '<p>El campo <strong>"Nacionalidad"</strong> es obligatorio.</p>',            
            'EdadPaciente.required' => '<p>El campo <strong>"Edad"</strong> es obligatorio.</p>',
            'OcupacionPaciente.required' => '<p>El campo <strong>"Ocupación"</strong> es obligatorio.</p>',
            'OcupacionPaciente.regex' => '<p>El campo <strong>"Ocupación"</strong> solo puede contener letras y espacios.</p>',
            'CorreoPaciente.email' => '<p>El campo <strong>"Correo electrónico"</strong> debe ser una dirección de correo válida.</p>',
            'TelefonoPaciente.regex' => '<p>El campo <strong>"Teléfono"</strong> debe contener solo números y tener 10 dígitos.</p>',   
            'CelularPaciente.regex' => '<p>El campo <strong>"Celular"</strong> debe contener solo números y tener 10 dígitos.</p>',
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            $mensaje = $errors->first();
            return response()->json(['mensaje'=>$mensaje],500);
        }else{
            return 1;
        }
    }

    public function ValidarPaciente($recien_nacido, $curp, $id_paciente){
        
            if(!$recien_nacido && $curp){
                $paciente = Pacientes::query();
                
                if($id_paciente){               
                    $paciente = $paciente->where('_id','!=',$id_paciente);
                }
                    $paciente = $paciente->where('CURP',$curp)->first();            
                if($paciente){
                    return 0;
                }else{
                    return 1;
                }
            }else{
                return 1;
            }
        
    }

    
}