<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Inertia\Response;
use App\Models\User;
use App\Models\UsuariosPerfiles;
use App\Models\Pacientes;
use App\Models\Consultas;
use MongoDB\BSON\ObjectId;
class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        /*$request->authenticate();
        
        $request->session()->regenerate();*/        
       
        $usuario = User::where('email', $request->correo)->first();
        if(!$usuario){
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
      
        if($usuario->status!=3 and $usuario->activo!=1){
            return response()->json(['error' => 'Tu acceso al sistema está restringido. Por favor, contacta al administrador o intenta nuevamente más tarde.'], 401);
        }


        

        if($usuario->rol<6){
            $perfil = UsuariosPerfiles::where('usuarioId',new ObjectId($usuario->_id))->first();
        }else{
            $perfil = Pacientes::where('usuario',new ObjectId($usuario->_id))->first();
        }
        //dd($perfil);

        if(Auth::loginUsingId($usuario->id)){
            Session::put('NombreUsuario',$perfil->nombre.' '.$perfil->primer_apellido.' '.$perfil->segundo_apellido);

            $inicial = strtoupper(substr($perfil->nombre, 0, 1));
            $inicialAp = strtoupper(substr($perfil->primer_apellido, 0, 1));

           
            Session::put('InicialesUsuario',$inicial.$inicialAp);
            Session::put('RolEtiquetaUsuario',$usuario->EtiquetaRol->titulo);
            Session::put('EmailUsuario',$usuario->email);
            Session::put('RolUsuario',$usuario->rol);
            Session::put('IdUsuario',$usuario->_id);
            Session::put('Dependencia',$usuario->rol == 3 ? $perfil->Dependencia->nombre : null);
            /*Session::put('Anio',date('Y'));*/
            if($usuario->rol==2){
                return redirect()->intended(route('importaciones-medicopasante', absolute: false));
            }
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/dashboard');
    }

    public function MensajeConfirmacion(Request $request)
    {                
        
        $consulta = Consultas::raw(function ($collection) use ($request) {
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
                    '$unwind' => '$paciente'
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
                    '$unwind' => '$medico'
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
                    '$unwind' => '$consultorio'
                ],
                [
                    '$match' => [
                        '_id' =>  new ObjectId($request->consulta),                  
                        'contador_folio' => (int) $request->folio,       
                        'paciente._id' => new ObjectId($request->paciente),
                    ],
                ],
             
                [
                    '$project'=>[
                        'paciente.nombre'=>1,
                        'paciente.primer_apellido'=>1,
                        'paciente.segundo_apellido'=>1,
                        'paciente_CURP'=>'$paciente.CURP',
                        'contador_folio'=>1,
                        'medico_nombre'=>'$medico.nombre',
                        'cedula_medico'=>'$medico.no_cuenta',
                        'created_at_formateado' => [
                            '$dateToString' => [
                                'format' => "%d-%m-%Y", // Día-Mes-Año
                                'date' => '$created_at'  // El campo 'created_at' que quieres formatear
                            ]
                            ],
                        'consultorio_nombre'=>'$consultorio.nombre',
                    ]
                ],
                  [
                    '$addFields' => [
                        'nombreCompleto' => [
                            '$concat' => [
                                ['$ifNull'=>['$paciente.primer_apellido','']],
                                ' ',
                                ['$ifNull'=>['$paciente.segundo_apellido','']],
                                ' ',
                                ['$ifNull'=>['$paciente.nombre','']],
                                '',
                            ]
                        ],
                    ]
                ]
            ]);
        })->first();

        if(!$consulta){
            return response()->json(['error' => 'Consulta no encontrada'], 404);
        }
        return $consulta;
     
    }
}
