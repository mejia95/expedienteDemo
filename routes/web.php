<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportacionesController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Inertia\Inertia;
use App\Exports\PacientesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\ConsultasController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\ReportesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EsResponsableSSMiddleware;
use App\Http\Middleware\ResponsableConsultorio;
use App\Http\Middleware\PermisoImportacionesMiddleware;


require __DIR__.'/auth.php';
require __DIR__.'/api.php';


Route::get('registro_mpss', [RegisteredUserController::class, 'create'])->name('registro_mpss');
Route::post('registro_mpss', [RegisteredUserController::class, 'ValidarMedicoRegistrado'])->name('registro_mps_post');

Route::get("registro-mpss/{id}/completar-registro",[RegisteredUserController::class, 'CompletarRegistro']);

Route::get('/completar-registro/{id}', [RegisteredUserController::class, 'CompletarRegistro'])->name('completar.registro');
Route::post('/completar-registro/{id}', [RegisteredUserController::class, 'CompletarRegistroStore'])->name('completar.registro.store');

Route::prefix('importaciones')->group(function () {
    Route::get("historial",[ImportacionesController::class,'historialImportaciones'])->middleware('auth');
    Route::get('medico-pasante',[ImportacionesController::class,'show'])->middleware(PermisoImportacionesMiddleware::class)->middleware('auth')->name('importaciones-medicopasante');
    Route::post('nueva',[ImportacionesController::class,'create'])->middleware('auth');
    Route::post('validar-importacion',[ImportacionesController::class,'VerificarMedicosRegistrados'])->middleware('auth');
    Route::delete('{importacion}/eliminar-importacion',[ImportacionesController::class,'EliminarImportacion'])->middleware('auth');
})->name('importaciones') ;

Route::prefix('personal/medico-pasante')->group(function () {
    Route::get('seleccionar-todos',[PersonalController::class,'seleccionarTodosMedicosPasantesPorEstado'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes');
    Route::get('',[PersonalController::class,'medicosPasantes'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes');
    Route::get('obtener',[PersonalController::class,'obtenerMedicosPasantesPorEstado'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes-obtener');
    Route::get('{id}/editar',[PersonalController::class,'mostrarInformacionMedico'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes-obtener');
    Route::post('{id}/editar',[PersonalController::class,'editarInformacionMedico'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes-obtener');
    Route::post('suspender-acceso',[PersonalController::class,'suspenderAccesoSistemaMedicosPasantes'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth'])->name('personal-medicospasantes-obtener');
}); 

Route::prefix("reportes")->group(function(){
    Route::get("suive",[ReportesController::class,'suive'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth']);
    Route::get("pacientes",[ReportesController::class,'MostrarPacientes'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth']);
});


Route::prefix('notificaciones')->group(function(){
    Route::post('medico-pasante/permitir-acceso',[NotificacionesController::class,'PermitirAccesoMedicoPasante'])->middleware(EsResponsableSSMiddleware::class)->middleware(['auth']);
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('pacientes/todos', function () {
    return Inertia::render('Pacientes/ListaPacientesGlobales',[
        'breadcrumbs'=>array(['title'=>'Todos los pacientes','href'=>'/pacientes/todos']),
        'ruta_valor'=>'pacientes'
    ]);
});
Route::get('pacientes/mis-pacientes', function () {
    return Inertia::render('Pacientes/ListaPacientes',[
        'breadcrumbs'=>array(['title'=>'Mis pacientes','href'=>'/pacientes/todos']),
        'ruta_valor'=>'pacientes'
    ]);
});

Route::get('pacientes/antecedentes', function () {
    return Inertia::render('Pacientes/Antecedentes',[
        'breadcrumbs'=>array(['title'=>'Antecedentes','href'=>'/pacientes/antecedentes']),
        'ruta_valor'=>'pacientes'
    ]);
});

Route::get('pacientes/nuevo', function () {
    return Inertia::render('Pacientes/NuevoPaciente',[
        'breadcrumbs'=>array(['title'=>'Nuevo paciente','href'=>'/pacientes/nuevo']),
        'ruta_valor'=>'pacientes'
    ]);
});

Route::get('pacientes/perfil/{id}', function ($id) {
    return Inertia::render('Pacientes/NuevoPaciente',
    [
        'id' => $id,
        // otros props...
    ]);
});

Route::get('pacientes/resumen/{id_paciente}/consulta/{id_consulta}', function ($id_paciente, $id_consulta) {
    return Inertia::render('ComponentsConsulta/Resumen',
    [
        'id_paciente' => $id_paciente,
        'id_consulta' => $id_consulta,
        // otros props...
    ]);
});

Route::get('pacientes/consulta/{id_paciente}', function ($id_paciente) {
    return Inertia::render('Pacientes/HistorialConsultas',
    [
        'id_paciente' => $id_paciente,
        // otros props...
    ]);
});

Route::get('consulta/nuevo/{id}', function ($id) {
    return Inertia::render('Consulta/FormularioConsulta',
    [
        'id' => $id,
        'breadcrumbs'=>array(['title'=>'Nuevo consulta','href'=>'#']),
        'ruta_valor'=>'pacientes'
        // otros props...
    ]);
});

//Pacientes Rutas
Route::group(['prefix'=>'pacientes'],function(){
	Route::get("paciente/{paciente}",[PacientesController::class,'ObtenerPerfilPaciente']);
	Route::get("lista-pacientes",[PacientesController::class,'ObtenerPacientes']);
	Route::get("lista/mis-pacientes",[PacientesController::class,'ObtenerMisPacientes']);
    //Route::get('antecedente',[PacientesController::class,'ObtenerAntecedentes']);    
	Route::post("registrar",[PacientesController::class,'GuardarPaciente']);
    Route::get("{id}/antecedente",[PacientesController::class,'ObtenerAntecedentes']);
    Route::put("/{id}/antecedentes/edit",[PacientesController::class,'ActualizarAntecedentes']);
	//Route::put("{paciente}/actualizar",[PacientesController::class,'ActualizarPaciente']);
	Route::get("{paciente}/consultas/historial",[PacientesController::class,'ObtenerHistorialConsultas']);
    Route::post("verificar_curp",[PacientesController::class,'VerificarCurp']);
    Route::put("vincular_sede", [PacientesController::class, 'VincularSede']);
});
Route::get('/exportar-pacientes', function () {
    return Excel::download(new PacientesExport, 'pacientes.xlsx');
});

Route::group(['prefix'=>'catalogos'],function(){
    Route::get('toxicomanias',[CatalogosController::class,'ConsultaToxicomanias']);    
    Route::post("toxicomanias/agregar", [CatalogosController::class, 'GuardarToxicomania']);
    Route::get("enfermedades-frecuentes", [CatalogosController::class, 'enfermedadesFrecuentes']);
    Route::post("enfermedades-frecuentes/agregar", [CatalogosController::class, 'agregarEnfermedadFrecuente']);
    Route::get('catalogos', [CatalogosController::class, 'Catalogos']);
    Route::get("agudeza-visual",[CatalogosController::class,'AgudezasVisuales']);
    Route::get("diagnosticos-todos",[CatalogosController::class,'DiagnosticosTodos']);    
    Route::get('medicamentos',[CatalogosController::class,'ConsultaMedicamentos']);
    Route::post('medicamentos/guardar/consultorio',[CatalogosController::class,'GuardarMedicamentosConsultorio']);
    Route::get('medicamentos/todos',[CatalogosController::class,'ConsultaMedicamentosTodos']);
    Route::get('medicamentos/consultorio',[CatalogosController::class,'ConsultaMedicamentosConsultorio']);
});

Route::group(['prefix'=>'consulta'],function(){
    Route::get("consulta_consultorios/{id}",[ConsultasController::class,'ConsultaConsultorios']);   
    Route::post("nueva",[ConsultasController::class,'create']);
    Route::get("consulta_paciente/{id}",[ConsultasController::class,'ConsultaDatosPaciente']);
    Route::put('agregar_clinica',[ConsultasController::class,'ActualizarConsulta']);
    Route::post("/{id}/tratamiento",[ConsultasController::class,'AgregarTratamiento']);
    Route::get('resumen/{id}',[ConsultasController::class,'ResumenConsulta']);
    Route::post('importarpdf/{id}',[ConsultasController::class,'ImportarPDF']);
    Route::group(['prefix'=>'{consulta}'],function(){
        Route::post("exploracion",[ConsultasController::class,'ObtenerRegistrarExploracion']);
        Route::get("exploracion/todo",[ConsultasController::class,'ObtenerExploracionTodos']);
        Route::get("exploracion/piel",[ConsultasController::class,'ObtenerExploracionPielTejidoDatos']);
        Route::get("exploracion/cabeza-cuello",[ConsultasController::class,'ObtenerExploracionCabezaCuello']);
        Route::post("estudios",[ConsultasController::class,'ObtenerEstudioLaboratorioConsulta']);
        Route::get("obten-diagnostico",[ConsultasController::class,'ObtenerDiagnostico']);
        Route::post("diagnostico",[ConsultasController::class,'GuardarDiagnostico']);
        Route::get("tratamiento",[ConsultasController::class,'ObtenerTratamiento']);
        //Route::get("antecedente",[ConsultasController::class,'ObtenerAntecedentes']);
    });
});

Route::get('lista/medicamentos', function () {
    return Inertia::render('Medicamentos/ListaMedicamentos');
})->middleware(ResponsableConsultorio::class)->middleware(['auth']);


////////////////// MEDICAMENTOS //////////////////////////////
Route::group(['prefix'=>'medicamentos'],function(){
    Route::get("lista",[CatalogosController::class,'ConsultaMedicamentos']);   
});
////////////////// PROPS ////////////////////
//Route::get('/pacientes/nuevo', [CatalogosController::class, 'Catalogos'])->name('pacientes.create');
//Route::get('/pacientes/nuevo/antecedentes', [CatalogosController::class, 'Toxicomanias'])->name('pacientes.create.antecedentes');
//


