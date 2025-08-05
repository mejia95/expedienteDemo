<?php

namespace App\Http\Controllers;
use App\Models\MedicosImportaciones;
use App\Models\DependenciasSS;
use App\Models\UsuariosPerfiles;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use MongoDB\BSON\ObjectId;
use App\Http\Repositorios\ImportacionRepositorio;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportacionesMedicosImport;
use Maatwebsite\Excel\HeadingRowImport;
use App\Services\ImportacionService;
use Illuminate\Support\Facades\Crypt;
use DB;
use Log;
use Auth;
class ImportacionesController extends Controller
{   
    protected $importacion;

    public function __construct(ImportacionRepositorio $importacion)
    {
        $this->importacion = $importacion;
    }

    /**
     * Muestra UI de importaciones
     */
    public function show()
    {

        $rol = Session::get('RolUsuario');
        $vista = 'importaciones/MedicosPSS';
        if($rol==1){
            $vista = 'importaciones/Adm-MedicosPSS';
        }
        return Inertia::render('importaciones/MedicosPSS',[
            'breadcrumbs'=>array(['title'=>'Importar datos','href'=>'/importar/medico-pss']),
            'ruta_valor'=>'importaciones'
        ]);
    }


    public function VerificarMedicosRegistrados (Request $request){
        $importacion =  $this->importacion->archivoImportacion(request()->file('archivo'));
        $encabezados_archivo = $importacion->ObtenerEncabezados();
        $encabezados_correctos_plantilla = $importacion->encabezadoValido($encabezados_archivo);
        if(!$encabezados_correctos_plantilla){
            return response()->json([
                'message' => 'El archivo no cumple con el formato requerido. Por favor, asegúrese de que el archivo contenga las siguientes columnas: NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO, NO_CUENTA, CORREO_INSTITUCIONAL, NOMBRE_DEPENDENCIA, ESTADO, MUNICIPIO, FECHA_INICIO_PROMOCION, FECHA_TERMINO_PROMOCION, LICENCIATURA'
            ], 400);
        }

        $importaciones = new ImportacionesMedicosImport();
        Excel::import($importaciones, request()->file('archivo'));
        $coleccion = $importaciones->rows;
        Log::info("Que traes en la coleccion");
        Log::info($coleccion);
        $registros_limpios = $this->importacion->VerificarDatosDuplicados($coleccion);
        return $registros_proceso_importacion = $this->importacion->ValidarInfoenBD($registros_limpios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
            // Desencriptar y decodificar los datos una sola vez
            $coleccion = collect(json_decode(Crypt::decryptString($request->mdUID), true));
            //$coleccion = $request->mdUID;
            if($coleccion->count()<1){
                return response()->json(['message'=>'No se pudo realizar la importación. El archivo no contiene registros válidos o está vacío. Por favor, asegúrate de que el archivo tenga datos correctos y vuelve a intentarlo.'],401);
            }
            $usuario = Auth::user()->id;
            // Crear la importación
            $nueva_importacion = MedicosImportaciones::create([
                'creado_por' => new ObjectId($usuario),
                'estado'=>1
            ]);

            // Importar los médicos y obtener el resultado
            return $importar_lista_medicos = $this->importacion->ImportarMedicos($coleccion, $nueva_importacion->_id);
            
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function historialImportaciones(Request $request)
    {
        //
        $pagina = $request->pagina ?? 1;
        $parametros_busqueda = $request;
        $historial = $this->importacion->historial($pagina,$parametros_busqueda);
        return $historial;
    }

    public function nueva(Request $request)
    {
        return $this->importacion->test();
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
    public function EliminarImportacion($importacion)
    {
        $userId = new ObjectId(Session::get('IdUsuario'));
        DB::beginTransaction();
        try{
            $importacionSeleccionada = MedicosImportaciones::where('_id',$importacion)->first();
            if(!$importacionSeleccionada){
                DB::rollBack();
                return response()->json([
                    'message' => 'No se encontró información de la importación seleccionada. Por favor, intente más tarde o contacte al soporte técnico.'
                ], 404);
            }

            // ¿ No tiene usuarios activos ? 
            $tieneUsuariosActivos =  $this->importacion->UsuariosImportacionCompletadoRegistro($importacion);
            if($tieneUsuariosActivos>0){
                DB::rollBack();
                return response()->json([
                    'message' => 'Existen usuarios activos asociados en el sistema. Si deseas eliminar algún médico, realiza esta operación desde la sección de personal.'
                ], 404);
            }

            // Importacion en progreso
            $importacionSeleccionada->update(['estado'=>2]);

            // Eliminar registros de la importacion
            $medicos_eliminados = $this->importacion->EliminarMedicosdeImportacion($importacion);

            // Finalizar importacion
            $importacionSeleccionada->update(['estado'=>0,'eliminado_por'=>$userId]);

            DB::commit();
            
            return response()->json(['message'=>"La importación ha sido eliminada exitosamente. Se han removido $medicos_eliminados médicos que previamente habían sido registrados."]);
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json(['message'=>'Ocurrió un error al eliminar la importación: '.$e->getMessage().' Por favor, intente nuevamente más tarde o contacte al soporte técnico si el problema persiste.'],404);
        }
    }
}
