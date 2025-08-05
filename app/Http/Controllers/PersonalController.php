<?php

namespace App\Http\Controllers;
use App\Http\Repositorios\ImportacionRepositorio;
use App\Http\Repositorios\PersonalRepositorio;
use App\Http\Requests\EditarInformacionMedicoRequest;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use App\Http\Repositorios\NotificacionesRepositorio;
use Inertia\Inertia;
use Log;
use Auth;
use Illuminate\Support\Facades\Storage;
use DB;
class PersonalController extends Controller
{
    
    protected $personal,$consultoriosRepo;

    public function __construct(PersonalRepositorio $personal, ImportacionRepositorio $consultoriosRepo,NotificacionesRepositorio $notificaciones)
    {
        $this->personal = $personal;
        $this->consultoriosRepo = $consultoriosRepo;
        $this->notificaciones =  $notificaciones;
    }

    public function medicosPasantes()
    {
        //
        return Inertia::render('personal/MedicosPasantes',[
            'breadcrumbs'=>array(['title'=>'Médico Pasante','href'=>'/personal/medico-pasante']),
            'ruta_valor'=>'personal'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function obtenerMedicosPasantesPorEstado(Request $request)
    {
        $personal = $this->personal;
        $pagina = $request->pagina ?? 1;
        $busqueda = $request->busquedaCampo ?? null;
        $getEstadoBusquedadePersonal = $personal->setEstadoUsuarios($request->estado);
        if(!$getEstadoBusquedadePersonal){
            return response()->json(['message'=>'No se pudo obtener la información solicitada.'], 401);
        }

       return $resultado = $personal->obtener($pagina,$busqueda);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function seleccionarTodosMedicosPasantesPorEstado(Request $request)
    {
        $personal = $this->personal;
        $getEstadoBusquedadePersonal = $personal->setEstadoUsuarios($request->estado);
        if(!$getEstadoBusquedadePersonal){
            return response()->json(['message'=>'No se pudo obtener la información solicitada.'], 401);
        }

       return response()->json($resultado = $personal->SeleccionarTodosPorEstado());
    }

    /**
     * Display the specified resource.
     */
    public function mostrarInformacionMedico(string $id)
    {
        $datosUsuario = $this->personal->obtenerDatosdelUsuario($id);
        if(!$datosUsuario){
            return redirect("/personal/medico-pasante");
        }
        $consultorios = $this->consultoriosRepo->ConsultoriosEstanRegistrados();
        return Inertia::render('personal/EditarMedicoPasante',[
            'breadcrumbs'=>array(['title'=>'Médicos Pasantes','href'=>'/personal/medico-pasante'],['title'=>"# $id",'href'=>'/personal/medico-pasante']),
            'InfoUsuario'=>$datosUsuario,
            'consultorios'=>$consultorios
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editarInformacionMedico(string $id, EditarInformacionMedicoRequest $request)
    {
       
        $datosUsuario = $this->personal->obtenerDatosdelUsuario($id);
        if(!$datosUsuario){
            return redirect("/personal/medico-pasante");
        }
        $archivo = null;
        if($request->cambioSede){
            $archivo =  Storage::putFile('medico-pasante/evidencias', $request->file('evidenciaCambioSede'));
        }
        
   


        $datos_Actualizar = ['email'=>$request->email,'observaciones'=>null,'motivo_correccion_informacion'=>$request->motivoCorreccion,'updated_by'=>new ObjectId(Auth::user()->id)];

        $DatosPerfilActualizar = [
                'nombre'=> mb_strtoupper($request->nombre),
                'primer_apellido'=>mb_strtoupper($request->primer_apellido),
                'segundo_apellido'=>mb_strtoupper($request->segundo_apellido),
                'no_cuenta'=>$request->no_cuenta,
                'consultorio'=>$request->sede ? new ObjectId($request->sede) : null,
                'consultorio_modificado'=>(int)$request->cambioSede ?? null,
                'motivo_cambio_consultorio'=>$request->motivoCambioSede ?? null,
                'evidencia_cambio_consultorio'=> $archivo ?? null,
                'updated_by'=>new ObjectId(Auth::user()->id),
                'licenciatura'=>mb_strtoupper($request->licenciatura),
        ];
        DB::beginTransaction();
        try{
            $actualizar_info_usuario = $this->personal->updateDatosUsuario($datosUsuario->_id,$datos_Actualizar);
            $actualizar_info_perfil_usuario = $this->personal->updateDatosPerfilUsuario($datosUsuario->_id,$DatosPerfilActualizar);
            DB::commit();

            $mensaje = "La información del usuario ha sido actualizada correctamente.";
            DB::beginTransaction();
            try{
                if($request->cambioSede){
                    if(!$request->sede){
                        $personal = $this->personal;
                        $suspender_acceso_medicos = $personal->SuspenderAccesoSistema([$datosUsuario->_id]);
                        $mensaje = "Se ha actualizado la información del usuario y suspendido el acceso al sistema, ya que no cuenta con un consultorio registrado";
                    }else{
                        Log::info("Entro al cambio de sede del controller");
                        $acceso_medicos_otorgados = $this->notificaciones->AutorizarAccesoMedicoPasante([$datosUsuario->_id]);
                        $mensaje = "La información del usuario ha sido actualizada y el cambio de consultorio se realizó correctamente. En breve, el usuario será notificado sobre su acceso.";
                    }
                    
    
                   
                }
                DB::commit();
            }catch(\Throwable $e){
                DB::rollBack();
                return response()->json(['message'=>'Ha ocurrido un error al actualizar la información, por favor intente de nuevo más tarde.'.$e],422);
            }
            
            return response()->json(['message'=>$mensaje]);
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json(['message'=>'Ha ocurrido un error al actualizar la información, por favor intente de nuevo más tarde.'.$e],422);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function suspenderAccesoSistemaMedicosPasantes(Request $request)
    {
        //
        $personal = $this->personal;
        $suspender_acceso_medicos = $personal->SuspenderAccesoSistema($request->medicos);
        return response()->json(['message'=>'Se ha suspendido el acceso a los usuarios seleccionados']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
