<?php
namespace App\Http\Repositorios;
use App\Models\DependenciasSS;
use App\Models\User;
use App\Models\UsuariosPerfiles;
use App\Models\MedicosImportaciones;
use Log;
use DB;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use Maatwebsite\Excel\HeadingRowImport;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Crypt;
class ImportacionRepositorio {
    public $archivo;
    private $ConsultoriosConsidenEstela = [];
    private $encabezados_plantilla = [
        'NOMBRE',
        'PRIMER_APELLIDO',
        'SEGUNDO_APELLIDO',
        'NO_CUENTA',
        'CORREO_INSTITUCIONAL',
        'NOMBRE_DEPENDENCIA',
        'ESTADO',
        'MUNICIPIO',
        'FECHA_INICIO_PROMOCION',
        'FECHA_TERMINO_PROMOCION',
        'LICENCIATURA'
    ];


    

    private function pipeline_importacion_con_usuarios_activos($importacion) {
        return [
            [
                '$project' => ['importacionId' => 1, 'usuarioId' => 1]
            ],
            [
                '$lookup' => [
                    'from' => 'users',
                    'localField' => 'usuarioId',
                    'foreignField' => '_id',
                    'as' => 'Usuario',
                   
                ]
            ],
            [
                '$unwind'=>'$Usuario'
            ],
            [
                '$match' => [
                    'importacionId' => new ObjectId($importacion),
                    'Usuario.status' => ['$nin' => [1, 4]]
                ]
            ],
            [
                '$group' => [
                    '_id' => null,
                    'TotalUsuariosCompletaronRegistro' => ['$sum' => 1]
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'TotalUsuariosCompletaronRegistro' => 1
                ]
            ]
        ];
    }
   
    public function archivoImportacion($archivo){
        $this->archivo = $archivo;
        return $this;
    }
    
    public function ObtenerEncabezados(){
        $archivo = $this->archivo;
        $headers =  (new HeadingRowImport)->toArray($archivo);
        return $headers[0][0];
    }

    public function encabezadoValido($encabezados){
        
        $tamanio_encabezados_plantilla = sizeof($this->encabezados_plantilla);
       
        $contador_columnas = 0;
        foreach ($encabezados as $encabezado) {
            if(in_array(strtoupper($encabezado),$this->encabezados_plantilla)){$contador_columnas += 1;}
        }
        //return intval($tamanio_encabezados_plantilla). "-".sizeof($encabezados)."-".$contador_columnas;
        return $contador_columnas == $tamanio_encabezados_plantilla;

    }

    

    private function convertirFechaExcel($fecha) {
        if (is_numeric($fecha)) {
            // Si es un número, es una fecha de Excel
            return date('Y-m-d', Date::excelToTimestamp($fecha));
        } else {
            // Si es una cadena, intentar convertirla normalmente
            $fecha_inicio_valido = trim($fecha, $character_mask = " \t\n\r\0\x0B");
            if(empty($fecha_inicio_valido)){return null;}
            return date('Y-m-d', strtotime($fecha));
        }
    }


    /**
     * Validar que la importacion seleccionada
     * sea valida para eliminar registros
     */

    public function UsuariosImportacionCompletadoRegistro($importacion)
    {
        //$importacion = "6851bf548b0d7a17660e2d1b";
        $pipeline = $this->pipeline_importacion_con_usuarios_activos($importacion);
        $usuariosActivos = DB::table('UsuariosPerfiles')->raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        })->toArray();

        $total = isset($usuariosActivos[0]['TotalUsuariosCompletaronRegistro']) 
            ? $usuariosActivos[0]['TotalUsuariosCompletaronRegistro'] 
            : 0;

        return $total;
    }

    public function EliminarMedicosdeImportacion($importacion){
        $campos_afectados = 0;
        $pipeline_usuarios_a_eliminar = [
            ['$lookup'=>[
                    'from'=>'users',
                    'localField'=>'usuarioId',
                    'foreignField'=>'_id',
                    'as'=>'Usuarios'
                ]
            ],
            [
                '$match'=> [
                    'importacionId'=>new ObjectId($importacion)
                ]
            ]
        ];
        $usuarios = DB::table('UsuariosPerfiles')->raw(function($coleccion) use ($pipeline_usuarios_a_eliminar) {
            return $coleccion->aggregate($pipeline_usuarios_a_eliminar);
        })->toArray();

        $ids_usuarios = array_map(function($usuario) {
            return  new ObjectId($usuario->usuarioId); // o $usuario['id'] según tu estructura
        }, $usuarios);
        
        Log::info("Que traes en ids_usuarios ");
        Log::info($ids_usuarios );
        
        if (!empty($ids_usuarios)) {
           
            DB::table('UsuariosPerfiles')
                ->whereIn('usuarioId', $ids_usuarios)
                ->delete();
            $campos_afectados =  DB::table('users')
                ->whereIn('_id', $ids_usuarios)
                ->delete();
        } 
        Log::info("Se han afectado $campos_afectados");

        return $campos_afectados;
    }

    /**
     * Registros duplicados en el archivo
     */
    
    public function VerificarDatosDuplicados($coleccion){
        $correos_registrados = [];
        $no_cuenta_registrados = [];
        $coleccion_limpia = [];
        foreach($coleccion as $registro){
            Log::info("VerificarDatosDuplicados");
            Log::info($registro['fecha_inicio_promocion']);
            if(!in_array($registro['correo_institucional'],$correos_registrados) and !in_array($registro['no_cuenta'],$no_cuenta_registrados)){
                $coleccion_limpia []= [
                    'correo_institucional'=>$registro['correo_institucional'],
                    'no_cuenta'=>$registro['no_cuenta'],
                    'nombre'=>$registro['nombre'],
                    'primer_apellido'=>$registro['primer_apellido'],
                    'segundo_apellido'=>$registro['segundo_apellido'],
                    'nombre_dependencia'=>$registro['nombre_dependencia'],
                    'estado'=>$registro['estado'],
                    'municipio'=>$registro['municipio'],
                    'fecha_inicio_promocion'=>$this->convertirFechaExcel($registro['fecha_inicio_promocion']),
                    'fecha_termino_promocion'=>$this->convertirFechaExcel($registro['fecha_termino_promocion']),
                    'licenciatura'=>$registro['licenciatura'],
                    'ok'=>1
                ];
                array_push($correos_registrados,$registro['correo_institucional']);
                array_push($no_cuenta_registrados,$registro['no_cuenta']);
            }else{
                $coleccion_limpia []= [
                    'correo_institucional'=>$registro['correo_institucional'],
                    'no_cuenta'=>$registro['no_cuenta'],
                    'nombre'=>$registro['nombre'],
                    'primer_apellido'=>$registro['primer_apellido'],
                    'segundo_apellido'=>$registro['segundo_apellido'],
                    'nombre_dependencia'=>$registro['nombre_dependencia'],
                    'estado'=>$registro['estado'],
                    'municipio'=>$registro['municipio'],
                    'fecha_inicio_promocion'=>$this->convertirFechaExcel($registro['fecha_inicio_promocion']),
                    'fecha_termino_promocion'=>$this->convertirFechaExcel($registro['fecha_termino_promocion']),
                    'licenciatura'=>$registro['licenciatura'],
                    'ok'=>0,
                    'error'=>'El registro no puede ser importado porque el correo electrónico o número de cuenta ya lo tiene asignado otro registro. Por favor, revise el archivo para identificar y corregir los registros duplicados.'
                ];
            }
        }

        return $coleccion_limpia;


    }


    public function ValidarInfoenBD($coleccion){
        $registros_a_importar = [];
        $registros_no_importar = [];

        $lista_correos_validar = [];
        $lista_cuentas_validar = [];

        foreach($coleccion as $registro){
            if($registro['ok']==1){
                array_push($lista_correos_validar,$registro['correo_institucional']);
                array_push($lista_cuentas_validar,$registro['no_cuenta']);
            }else{
                $registros_no_importar []= $registro;
            }
        }

        $usuarios_existentes = $this->ValidarExistenciaMedicos($lista_correos_validar,$lista_cuentas_validar);
        
        // Verificar cada registro con ok=1
        foreach($coleccion as $registro){
            if($registro['ok']==1){
                $existe = $usuarios_existentes->contains(function($usuario) use ($registro) {
                    return $usuario->email === $registro['correo_institucional'] || 
                           $usuario->no_cuenta === $registro['no_cuenta'];
                });

                if($existe){
                    $registro['ok'] = 0;
                    $registro['error'] = 'El registro no puede ser importado porque el correo electrónico o número de cuenta ya está registrado en el sistema. Por favor, verifique que no exista un registro previo con estos datos.';
                    $registros_no_importar[] = [
                        'nombre'=>$registro['nombre'],
                        'primer_apellido'=>$registro['primer_apellido'],
                        'segundo_apellido'=>$registro['segundo_apellido'],
                        'no_cuenta'=>$registro['no_cuenta'],
                        'nombre_dependencia'=>$registro['nombre_dependencia'],
                        'correo_institucional'=>$registro['correo_institucional'],
                        'error'=>$registro['error'],
                    ];
                }else{
                    $registros_a_importar[] = $registro;
                }
            }
        }

        return [
            'registros_a_importar' => $registros_a_importar,
            'registros_no_importar' => $registros_no_importar,
            'total_a_importar' => count($registros_a_importar),
            'mdUID' => Crypt::encryptString(json_encode($registros_a_importar)),
            'total_no_importar' => count($registros_no_importar)
        ];
    }



    public function ConsultoriosEstanRegistrados(){
        $this->ConsultoriosConsidenEstela = DependenciasSS::get(['_id','nombre']);
        return $this->ConsultoriosConsidenEstela;
    }

    public function ConsultorioMedicoDisponible($archivo_consultorio_medico){
        if(empty(trim($archivo_consultorio_medico))){return false;}
        
        $ConsultoriosPermitidos = $this->ConsultoriosConsidenEstela;
        return $ConsultoriosPermitidos->filter(function($consultorio) use ($archivo_consultorio_medico) {
            return mb_strtoupper(trim($archivo_consultorio_medico,$character_mask = " \t\n\r\0\x0B"), 'UTF-8') == $consultorio->nombre;
        })->first();
    }

    /**
     * Validar que el correo corresponda 
     * a @uaemex o @alumno.uaemex
     */
    public function ValidarCorreoInstitucional($correo){
        $correo_valido = trim($correo, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($correo_valido)){return 0;}
        
        return preg_match('/@(uaemex|alumno\.uaemex)\.mx$/', strtolower($correo_valido)) ? 1 : 0;
    }
    public function ValidarNombrePrimerApellido($nombre,$primer_apellido){
        $nombre_valido = trim($nombre, $character_mask = " \t\n\r\0\x0B");
        $primer_apellido_valido = trim($primer_apellido, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($nombre_valido) || empty($primer_apellido_valido)){return false;}
        return 1;
        
    }
    public function ValidarNoCuenta($cuenta){
        $cuenta_valido = trim($cuenta, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($cuenta_valido)){return false;}
        return 1;
    }
    public function ValidarDependencia($dependencia){
        $dependencia_valido = trim($dependencia, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($dependencia_valido)){return false;}
        return 1;
        
    }
    public function ValidarEstado($estado){
        $estado_valido = trim($estado, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($estado_valido)){return false;}
        return 1;
        
    }
    public function ValidarMunicipio($municipio){
        $municipio_valido = trim($municipio, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($municipio_valido)){return false;}
        return 1;
        
    }
    public function ValidarFechaInicio($fecha_inicio){
        $fecha_inicio_valido = trim($fecha_inicio, $character_mask = " \t\n\r\0\x0B");
        if(empty($fecha_inicio_valido)){return false;}
        return 1;
        
    }
    public function ValidarFechaTermino($fecha_termino){
        $fecha_termino_valido = trim($fecha_termino, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($fecha_termino_valido)){return false;}
        return 1;
        
    }
    public function ValidarLicenciatura($licenciatura){
        $licenciatura_valido = trim($licenciatura, $character_mask = " \t\n\r\0\x0B");
        
        if(empty($licenciatura_valido)){return false;}
        return 1;
        
    }


    /**
     * Validar que el registro tenga fecha de inicio y termino 
     * valida en formato de fecha
     */

    public function ValidarPeriodoPromocion($fecha_inicio,$fecha_termino){
        Log::info("Que traes en la fecha inicio");
        Log::info($fecha_inicio);
        $fecha_inicio_valida = trim($fecha_inicio,$character_mask = " \t\n\r\0\x0B");
        $fecha_termino_valida = trim($fecha_termino,$character_mask = " \t\n\r\0\x0B");
        
        return !empty($fecha_inicio) && !empty($fecha_termino_valida);
    }

    /**
     * Obtener lista de usuarios
     * registrados y no registrados en el sistema
     */
    public function ValidarExistenciaMedicos($correos_medicos, $cuentas_medicos)
    {
        
        $pipeline = [
            [
                '$match' => [
                    'rol' => 3
                ]
            ],
            [
                '$project' => ['email' => 1, '_id' => 1]
            ],
            [
                '$lookup' => [
                    'from' => 'UsuariosPerfiles',
                    'localField' => '_id',
                    'foreignField' => 'usuarioId',
                    'as' => 'PerfilUsuario'
                ]
            ],
            [
                '$unwind' => '$PerfilUsuario'
            ],
            [
                '$addFields' => [
                    'no_cuenta' => '$PerfilUsuario.no_cuenta'
                ]
            ],
            [
                '$match' => [
                    '$or' => [
                        ['no_cuenta' => ['$in' => $cuentas_medicos]],
                        ['email' => ['$in' => $correos_medicos]]
                    ]
                ]
            ],

        ];

        return User::raw(function ($coleccion) use ($pipeline) {
            return $coleccion->aggregate($pipeline);
        });
    }

   

    public function ImportarMedicos($lista_medicos,$importacion){
        if(sizeof($lista_medicos)<1){return false;}
        $nueva_info_user = [];
        $hoy = now();
        $nueva_info_perfil = [];
        $registros_importados = [];
        $registros_no_importados = [];
        $DependenciasRegistradasEstela = $this->ConsultoriosEstanRegistrados();
        foreach($lista_medicos as $info_medico){
                $medico = mb_strtoupper($info_medico['nombre'])." ".mb_strtoupper($info_medico['primer_apellido'])." ".mb_strtoupper($info_medico['segundo_apellido']);
                $consultorio = mb_strtoupper($info_medico['nombre_dependencia']);
                $permitir_registro_medico_por_consultorio = $this->ConsultorioMedicoDisponible($info_medico['nombre_dependencia']);
                //Valida que el correo no este en el sistema
                $correo_existe = User::where('email',strtolower($info_medico['correo_institucional']))->count();
                if($correo_existe>0){
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El correo electrónico proporcionado ya está registrado en el sistema. Por favor, verifica que el correo sea correcto o utiliza uno diferente.'
                    ];
                    continue;
                }
                
                //Valida que el correo solo sea uaemex
                $correo_valido =  $this->ValidarCorreoInstitucional($info_medico['correo_institucional']);
                if(!$correo_valido){
                    
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El correo electrónico no es válido. Debe ser un correo institucional (@uaemex.mx o @alumno.uaemex.mx)'
                    ];
                    continue;
                }
                $nombre_valido =  $this->ValidarNombrePrimerApellido($info_medico['nombre'],$info_medico['primer_apellido']);
                if(!$nombre_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque el nombre o primer apellido están vacíos. Por favor, complete estos campos obligatorios.'
                    ];
                    continue;
                }
                $cuenta_valido =  $this->ValidarNoCuenta($info_medico['no_cuenta']);
                if(!$cuenta_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque el número de cuenta está vacío. Por favor, proporcione un número de cuenta válido.'
                    ];
                    continue;
                }
                $nombre_dependencia_valido =  $this->ValidarDependencia($info_medico['nombre_dependencia']);
                if(!$nombre_dependencia_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque la dependencia está vacía. Por favor, especifique la dependencia o consultorio al que pertenece el médico.'
                    ];
                    continue;
                }
                $estado_valido =  $this->ValidarEstado($info_medico['estado']);
                if(!$estado_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque el estado está vacío. Por favor, especifique el estado donde se encuentra la dependencia.'
                    ];
                    continue;
                }
                $municipio_valido =  $this->ValidarMunicipio($info_medico['municipio']);
                if(!$municipio_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque el municipio está vacío. Por favor, especifique el municipio donde se encuentra la dependencia.'
                    ];
                    continue;
                }
                $fecha_inicio_promocion_valido =  $this->ValidarFechaInicio($info_medico['fecha_inicio_promocion']);
                if(!$fecha_inicio_promocion_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque la fecha de inicio de promoción está vacía. Por favor, especifique la fecha de inicio del periodo de promoción.'
                    ];
                    continue;
                }
                $fecha_termino_promocion_valido =  $this->ValidarFechaTermino($info_medico['fecha_termino_promocion']);
                if(!$fecha_termino_promocion_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque la fecha de término de promoción está vacía. Por favor, especifique la fecha de término del periodo de promoción.'
                    ];
                    continue;
                }
                $licenciatura_valido =  $this->ValidarLicenciatura($info_medico['licenciatura']);
                if(!$licenciatura_valido){
                   
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no puede ser importado porque la licenciatura está vacía. Por favor, especifique la licenciatura o carrera del médico.'
                    ];
                    continue;
                }




                //Valida que las fechas de promocion no esten vacias
                $periodo_promocion_valido = $this->ValidarPeriodoPromocion($info_medico['fecha_inicio_promocion'],$info_medico['fecha_termino_promocion']);

                if(!$periodo_promocion_valido){
                    Log::info("entra aqui por que no ay fechas");
                    $correo =  $info_medico['correo_institucional'];
                    $medico = mb_strtoupper($info_medico['nombre'])." ".mb_strtoupper($info_medico['primer_apellido'])." ".mb_strtoupper($info_medico['segundo_apellido']);
                    $consultorio = mb_strtoupper($info_medico['nombre_dependencia']);
                    $registros_no_importados[] = [
                        'medico'=>$medico,
                        'consultorio'=>$consultorio,
                        'cuenta'=>$info_medico['no_cuenta'],
                        'correo_institucional'=>$info_medico['correo_institucional'],
                        'error' => 'El registro no se pudo importar porque las fechas del periodo de promoción no son válidas. Por favor, verifica que las fechas de inicio y término estén correctamente registradas en el formato adecuado.'
                    ];
                }else{
                    if($permitir_registro_medico_por_consultorio){
                        $correo =  $info_medico['correo_institucional'];
                        $consultorioID = $permitir_registro_medico_por_consultorio->_id;
                        $medico = mb_strtoupper($info_medico['nombre'])." ".mb_strtoupper($info_medico['primer_apellido'])." ".mb_strtoupper($info_medico['segundo_apellido']);
                        $consultorio = mb_strtoupper($info_medico['nombre_dependencia']);
                       
                            
                            $userId = new ObjectId();
                            $nueva_info_user [] = ['email'=>strtolower($correo),'rol'=>3,'status'=>1,'activo'=>0,'_id'=>$userId,'created_at'=>$hoy,'updated_at'=>$hoy];
                            $nueva_info_perfil [] = [
                                'nombre'=>mb_strtoupper($info_medico['nombre']),
                                'primer_apellido'=>mb_strtoupper($info_medico['primer_apellido']),
                                'segundo_apellido'=>mb_strtoupper($info_medico['segundo_apellido']),
                                'consultorio'=>new ObjectId($permitir_registro_medico_por_consultorio->_id),
                                'no_cuenta'=>$info_medico['no_cuenta'],
                                'licenciatura'=>mb_strtoupper($info_medico['licenciatura']),
                                'fecha_inicio_promocion'=>date('Y-m-d',strtotime($info_medico['fecha_inicio_promocion'])),
                                'fecha_termino_promocion'=>date('Y-m-d',strtotime($info_medico['fecha_termino_promocion'])),
                                'importacionId'=>new ObjectId($importacion),
                                'usuarioId'=>$userId,
                                'created_at'=>$hoy,
                                'updated_at'=>$hoy
                            ];
    
                            $registros_importados[] = [
                                'medico'=>$medico,
                                'cuenta'=>$info_medico['no_cuenta'],
                                'correo_institucional'=>$info_medico['correo_institucional'],
                                'consultorio'=>$consultorio,
                            ];
                       
                    } else {
                        $medico = mb_strtoupper($info_medico['nombre'])." ".mb_strtoupper($info_medico['primer_apellido'])." ".mb_strtoupper($info_medico['segundo_apellido']);
                        $consultorio = mb_strtoupper($info_medico['nombre_dependencia']);
                        $registros_no_importados[] = [
                            'medico'=>$medico,
                            'consultorio'=>$consultorio,
                            'cuenta'=>$info_medico['no_cuenta'],
                            'correo_institucional'=>$info_medico['correo_institucional'],
                            'error' => 'El usuario no se pudo importar porque el consultorio asignado no existe en el sistema. Por favor, verifica que el consultorio esté registrado o agrégalo al sistema antes de importar el usuario.'
                        ];
                    }
                }
                
           
        }
        
        
        try {
            $nuevos_usuarios = User::insert($nueva_info_user);
            $nuevos_usuarios = UsuariosPerfiles::insert($nueva_info_perfil);


            $actualizar_registro_importacion = MedicosImportaciones::where('_id',$importacion)
                ->update([
                    'registros_importados'=>$registros_importados,
                    'estado'=>1,
                    'registros_no_importados'=>$registros_no_importados
                ]);
            return response()->json([
                    'status' => 'success',
                    'message' => 'Importación completada exitosamente',
             ]);
        } catch (\Exception $e) {
            Log::error("Error al actualizar registro de importación: " . $e->getMessage());
            $actualizar_registro_importacion = MedicosImportaciones::where('_id',$importacion)
                ->update([
                    'estado'=>0,
                    'comentario'=>"Error al actualizar registro de importación: " . $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar la importación de medicos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function historial($pagina,$parametros){
        $informacion = [];
        $informacion['data']=[];
        $i = 0;
        $busqueda = $parametros['busqueda'] ?? null;
        $fecha_inicio = $parametros['fecha_inicio'] ?? null;
        $fecha_fin = $parametros['fecha_fin'] ?? null;
        $limite = 10;
        $offset = ($pagina - 1) * $limite; 
        $pipeline = [
            [
                '$match' => [
                    'estado' => 1
                ]
            ],
            [
                '$project'=>[
                    '_id'=>1,
                    'fecha_creacion'=>[
                        '$dateToString'=>[
                            'format'=>"%d %b %Y %H:%M:%S",
                            'date'=>'$created_at',
                            'timezone'=> 'America/Mexico_City'
                        ]
                    ],
                    'fecha_creacion_format'=>[
                        '$dateToString'=>[
                            'format'=>"%Y-%m-%d",
                            'date'=>'$created_at',
                            'timezone'=> 'America/Mexico_City'
                        ]
                    ],
                    'creado_por'=>1,
                    'registros_importados'=>1,
                    'registros_no_importados'=>1
                    
                ]
            ],
            [
                '$lookup'=>[
                    'from'=>'users',
                    'localField'=>'creado_por',
                    'foreignField'=>'_id',
                    'as'=>'Usuario',
                    'pipeline'=>[
                        [
                            '$lookup'=>[
                                'from'=>'UsuariosPerfiles',
                                'localField'=>'_id',
                                'foreignField'=>'usuarioId',
                                'as'=>'Perfil',
                            ]
                        ],
                        [
                           '$unwind'=>'$Perfil' 
                        ]
                    ]
                ]
            ],
            [
                '$unwind'=>'$Usuario'
            ],
            [
                '$lookup'=>[
                   'from'=>'UsuariosPerfiles',
                    'localField'=>'_id',
                    'foreignField'=>'importacionId',
                    'as'=>'PerfilesdeImportacion', 
                    'pipeline'=>[
                        [
                            '$lookup'=>[
                                'from'=>'users',
                                'localField'=>'usuarioId',
                                'foreignField'=>'_id',
                                'as'=>'Usuario'
                            ]
                        ],
                        [
                            '$unwind'=>'$Usuario'
                        ],
                        [
                            '$match'=>[
                                'Usuario.status' => ['$nin' => [1, 4]]
                            ]
                        ],
                        [
                            '$group'=>[
                                '_id'=>'$importacionId',
                                'total'=>['$sum'=>1]
                            ]
                        ],
                        [
                            '$addFields'=>[
                                'totales'=>'$total'
                            ]
                        ]
                    ]
                ]
            ],    
            [
                '$unwind'=>['path'=>'$PerfilesdeImportacion','preserveNullAndEmptyArrays'=>true],
            ],        
            [
                '$addFields'=>[
                    'Usuario'=>[
                        '$concat'=>[
                            ['$ifNull'=>['$Usuario.Perfil.nombre','']],
                            ' ',
                            ['$ifNull'=>['$Usuario.Perfil.primer_apellido','']],
                            ' ',
                            ['$ifNull'=>['$Usuario.Perfil.segundo_apellido','']]
                        ]
                    ],
                    'MedicosActivosImportacion'=>[
                        '$ifNull'=>['$PerfilesdeImportacion.total', 0]
                    ],
                    'HID'=>[
                        '$toString'=>'$_id'
                    ]
                ]
            ],
            [
                '$project'=>['creado_por'=>0,'PerfilesdeImportacion'=>0]
            ],
            [
                '$sort'=>['_id'=>-1]
            ]
            
        ];

        if($busqueda){
            array_push($pipeline,['$match'=>[
                'HID'=> new Regex($busqueda,'i')
            ]]);
        }
        
        if($fecha_inicio){
            array_push($pipeline,['$match'=>[
                'fecha_creacion_format'=> ['$gte'=>$fecha_inicio]
            ]]);
        }
        
        if($fecha_fin){
            array_push($pipeline,['$match'=>[
                'fecha_creacion_format'=> ['$lte'=>$fecha_fin]
            ]]);
        }

        $totalRegistros =  MedicosImportaciones::raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        })->count();

        
        array_push($pipeline,['$skip'=>$offset],['$limit'=>$limite]);

        $historial = MedicosImportaciones::raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        });
        $informacion['total']=number_format($totalRegistros);
        $informacion['last_page']=ceil ($totalRegistros / $limite);
        $last_page = ceil($totalRegistros / $limite);

        foreach ($historial as $historico) {
            $informacion['data'][]=[
                "index"=>$pagina * $limite - $limite+1 + $i,
                'registros_importados'=>$historico['registros_importados'] ?? [],
                'total_registros_importados'=>$historico['registros_importados'] ? sizeof($historico['registros_importados']) : 0,
                'registros_no_importados'=>$historico['registros_no_importados'] ?? [],
                "usuario"=>$historico['Usuario'],
                "hID"=>$historico['_id'],
                "fecha_creacion"=>$historico['fecha_creacion'],
                'medicosActivos'=>$historico['MedicosActivosImportacion']
            ];
            $i++;
        }

        $to = $pagina < $last_page ? sizeof($informacion['data']) * $pagina: $informacion['total'];

        $informacion['to']=number_format($to);
        $informacion['per_page']=$limite;
        $informacion['pagina']=$pagina;

        return $informacion;

    }

    public function eliminar(){
        $this->archivo = null;
    }
}