<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MedicoPasantePreRegistros;
use App\Http\Repositorios\RegistroMedicosPasantesRepositorio;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use MongoDB\BSON\ObjectId;
use Log;
use DB;
class RegisteredUserController extends Controller
{
    private $registroRepositorio;
    public function __construct(RegistroMedicosPasantesRepositorio $registroRepositorio){
        $this->registroRepositorio =  $registroRepositorio;
    }
    /**
     * Show the registration page.
     */
    public function create(Request $request): Response
    {
    
        if($request->has('data')){
            $decode_response = json_decode(base64_decode($request->data),true);
        
            $error = $decode_response['error'];
            if($error != 0){
                return redirect('/login'); 
            }
            $correo_des = $decode_response['empleado']['correo'];
            $usuario_sistema = User::where('email',$correo_des)->where('rol',3)->first();
            if(!$usuario_sistema){
                return redirect('/login'); 
            }

            if($usuario_sistema->status==1){

                
                DB::beginTransaction();
                try {
                    
                    $usuario_sistema->update(['status'=>4]);
                    $nueva_solicitud_expiracion = MedicoPasantePreRegistros::create(
                        [
                            'usuarioId'=>new ObjectId($usuario_sistema->id),
                            'fecha_solicitud'=>now(),
                            'tiempo_expiracion_solicitud_segundos'=>300,
                            'status_solicitud'=>1
                        ]);
                    Mail::send('emails.completar_registro', ['usuario' => $usuario_sistema,'enlace'=>$nueva_solicitud_expiracion], function($message) use ($usuario_sistema) {
                            $message->to($usuario_sistema->email)
                                ->subject('Completar acceso - Expediente MPSS');
                    });
                    DB::commit();
                    return Inertia::render('auth/Registrompss', [
                        'mensaje' => 'Su solicitud ha sido enviada exitosamente. El responsable de servicio social revisará su información y le notificará cuando su acceso sea autorizado.'
                    ]);
                    //return response()->json(['message'=>'Su solicitud ha sido enviada exitosamente. El responsable de servicio social revisará su información y le notificará cuando su acceso sea autorizado.']);
            
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect('/login'); 

                }




            }
        }
        return Inertia::render('auth/Registrompss');
    }

    /**
     * Verifica que el correo exista en el sistema como
     * Medico pasante
     */

     public function ValidarMedicoRegistrado(Request $request){
       
        $usuario_sistema = User::where('email',$request->correo)->where('rol',3)->first();
        if(!$usuario_sistema){
            return response()->json(['message'=>'El correo electrónico proporcionado no está registrado en el sistema. Por favor, verifique que el correo sea correcto o contacte al responsable de servicio social si necesita ayuda.'],401);
        }

        
        if($usuario_sistema->status==1){
            $url = $this->registroRepositorio->LogeoOffice365($request->correo);
            
            return response()->json(['redirectTo'=>$url]);
            
            
        }

        if($usuario_sistema->status==4){
            $solicitud_acceso_valida = $this->registroRepositorio->ProcesoAutenticacionUsuarioValido($usuario_sistema->id);
            if(!$solicitud_acceso_valida){
                $enlace_info = MedicoPasantePreRegistros::where('usuarioId',new ObjectId($usuario_sistema->id))->where('status_solicitud',1)->first();
                $reinicio_status_usuario =  User::where('_id',new ObjectId($enlace_info->usuarioId))->update(['status'=>1]);
                $enlace_info->update(['status_solicitud'=>0]);
                return response()->json(['message'=>'El tiempo para completar el proceso de validación ha expirado. Por favor, inicia nuevamente el proceso de registro.'],401);
            }
            
            
            
        }

        

        $mensajeSolicitud = $this->registroRepositorio->ObtenerEstadoSolicitud($usuario_sistema->status);
        return response()->json(['message'=>$mensajeSolicitud],401);

       
        
     }

     public function CompletarRegistro($id){
        //Validar que el usuario tenga status 4 para permitir la validacion de info

        

        
        try{
            $enlace_info = MedicoPasantePreRegistros::where('_id',new ObjectId($id))->first();
            $usuario_enlace =  User::where('_id',new ObjectId($enlace_info->usuarioId))->first();
            if($usuario_enlace->status!=4){
                return redirect('/registro_mpss');
            }
            $enlace_valido =  $this->registroRepositorio->enlaceValido($id);
            if(!$enlace_valido){
                $enlace_info = MedicoPasantePreRegistros::where('_id',new ObjectId($id))->first();
                $reinicio_status_usuario =  User::where('_id',new ObjectId($enlace_info->usuarioId))->update(['status'=>1]);
                $enlace_info->update(['status_solicitud'=>0]);
                return redirect('/registro_mpss');
            }

            // Get the registration info first
            $enlace_info = MedicoPasantePreRegistros::where('_id', new ObjectId($id))->first();
            
            // Then get the user data with their profile
            $pipeline = [
                [
                    '$match' => [
                        '_id' => new ObjectId($enlace_info->usuarioId)
                    ]
                ],
                [
                    '$lookup' => [
                        'from' => 'UsuariosPerfiles',
                        'localField' => '_id',
                        'foreignField' => 'usuarioId',
                        'as' => 'Perfil',
                        'pipeline'=>[
                            [
                                '$lookup'=>[
                                    'from'=>'DependenciasSS',
                                    'localField'=>'consultorio',
                                    'foreignField'=>'_id',
                                    'as'=>'Consultorio'
                                ],
                            ],
                            [
                                '$unwind'=>'$Consultorio'
                            ]
                            
                            
                        ]
                    ]
                ],
                [
                    '$unwind' => '$Perfil'
                ],
                [
                    '$addFields'=>[
                        'sede'=>'$Perfil.Consultorio.nombre',
                        'nombre'=>'$Perfil.nombre',
                        'primer_apellido'=>'$Perfil.primer_apellido',
                        'segundo_apellido'=>'$Perfil.segundo_apellido',
                        'no_cuenta'=>'$Perfil.no_cuenta',
                        'fecha_inicio'=>'$Perfil.fecha_inicio_promocion',
                        'fecha_termino'=>'$Perfil.fecha_termino_promocion',
                    ]
                ],
                [
                    '$project'=>['_id'=>1,'Perfil'=>0]
                ]
            ];

            $infoUsuario = User::raw(function($coleccion) use ($pipeline) {
                return $coleccion->aggregate($pipeline);
            })->first();

            return Inertia::render('auth/CompletarRegistroMedicoPSS',[
                'registroUID'=>$id,
                'medicoPasante'=>$infoUsuario ?? null
            ]);
        }catch(\Throwable $e){
            //return $e;
            return redirect('/registro_mpss');
        }



        
     }
     public function CompletarRegistroStore($id, Request $request){
        
        try {
            $request->validate([
                'observaciones' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\p{P}\p{Z}\n\r]+$/u']
            ], [
                'observaciones.max' => 'Las observaciones no pueden exceder los 255 caracteres.',
                'observaciones.regex' => 'Las observaciones solo pueden contener letras, números, espacios, saltos de línea y signos de puntuación comunes. No se permiten emoticonos ni caracteres especiales.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->errors()['observaciones'][0]
            ], 401);
        }


        try{
            $enlace_info = MedicoPasantePreRegistros::where('_id',new ObjectId($id))->first();
            $pipeline = [
                ['$lookup'=>[
                    'from'=>'UsuariosPerfiles',
                    'localField'=>'_id',
                    'foreignField'=>'usuarioId',
                    'as'=>'Perfil'
                    ]
                ],
                [
                    '$unwind'=>'$Perfil'
                ],
                [
                    '$addFields'=>[
                        'nombre'=>'$Perfil.nombre',
                        'primer_apellido'=>'$Perfil.primer_apellido',
                        'segundo_apellido'=>'$Perfil.segundo_apellido',
                        'no_cuenta'=>'$Perfil.no_cuenta',
                        'correo'=>'$email',
                    ]
                ],
                [
                    '$match'=>[
                        '_id'=>new ObjectId($enlace_info->usuarioId)
                    ]
                ]
                    ];
            
            $status_usuario =  User::where('_id',new ObjectId($enlace_info->usuarioId))->first();
            
            $status_usuario =  User::raw(function($coleccion) use($pipeline){
                return $coleccion->aggregate($pipeline);
            })->first();
            
            if($status_usuario->status!=4){
                return response()->json(['status'=>1,'message'=>'El enlace de validación ha expirado o ya no está disponible. Por favor, inicia nuevamente el proceso de registro.'],401);
            }
            $enlace_valido =  $this->registroRepositorio->enlaceValido($id);
            if(!$enlace_valido){
                $enlace_info = MedicoPasantePreRegistros::where('_id',new ObjectId($id))->first();
                $reinicio_status_usuario =  User::where('_id',new ObjectId($enlace_info->usuarioId))->update(['status'=>1]);
                $enlace_info->update(['status_solicitud'=>0]);
                return redirect('/registro_mpss');
            }
            $enlace_info = MedicoPasantePreRegistros::where('_id',new ObjectId($id))->first();
            $cambio_status_usuario =  User::where('_id',new ObjectId($enlace_info->usuarioId))->update(['status'=>2,'observaciones'=>$request->observaciones ?? null,'fecha_solicitud_acceso'=>now()]);
            $enlace_info->update(['status_solicitud'=>0]);

            //Obtener correo del responsable para mandar el correo
        
        
        
            $responsable = $this->registroRepositorio->CorreoResponsabledeServicioSocial();
            if($responsable){
                $usuario = User::where('_id', new ObjectId($enlace_info->usuarioId))->first();
                $perfil = DB::table('UsuariosPerfiles')->where('usuarioId', new ObjectId($enlace_info->usuarioId))->first();
                
                Mail::send('emails.notificacion-solicitudes-pendientes', [
                    'observaciones' => $request->observaciones,
                    'nombre' => $status_usuario->nombre ?? '',
                    'primer_apellido' => $status_usuario->primer_apellido ?? '',
                    'segundo_apellido' => $status_usuario->segundo_apellido ?? '',
                    'correo' => $status_usuario->correo ?? ''
                ], function($message) use($responsable) {
                    $message->to($responsable)
                        ->subject('Solicitudes de Acceso de Médicos de Servicio Social Pendientes - Expediente MPSS');
                });
            }
            // Enviar correo al responsable
            

            return response()->json(['message'=>'Su registro ha sido completado exitosamente. Recibirá un correo electrónico con la confirmación de su acceso al sistema.']);



        }catch(\Thowable $e){
            return response()->json([
                'status' => 1,
                'message' => 'El enlace de validación ha expirado o ya no está disponible. Por favor, inicia nuevamente el proceso de registro.'
            ], 401);
        }

    }
}
