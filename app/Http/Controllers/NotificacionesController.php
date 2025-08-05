<?php

namespace App\Http\Controllers;
use App\Http\Repositorios\NotificacionesRepositorio;
use Illuminate\Http\Request;
use DB;
class NotificacionesController extends Controller
{
    private $notificaciones;
    public function __construct(NotificacionesRepositorio $notificaciones){
        $this->notificaciones =  $notificaciones;
    }
    
    public function PermitirAccesoMedicoPasante(Request $request)
    {
        try {
            $medicos = $request->medicos;
            $acceso_medicos_otorgados = $this->notificaciones->AutorizarAccesoMedicoPasante($medicos);
            return response()->json(['message' => 'Se autorizó el acceso correctamente a ' . $acceso_medicos_otorgados . ' médicos pasantes']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'No se pudo actualizar el acceso de los médicos pasantes. Por favor, intente nuevamente.'.$e
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
