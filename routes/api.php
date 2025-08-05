<?php

use Illuminate\Support\Facades\Route;
use App\Http\Repositorios\CatalogosRepo;
use App\Http\Repositorios\EstadisticosRepositorio;
use App\Http\Controllers\ReportesController;
Route::prefix('api')->group(function(){
    Route::prefix('catalogos')->group(function(){
        Route::get("consultorios",[CatalogosRepo::class,'getConsultoriosTodos']);
        Route::get("generos",[CatalogosRepo::class,'getGenerosTodos']);
    });
   
    Route::group(['prefix'=>'reportes'],function(){
        Route::get("suive-obtener",[ReportesController::class,'getSUIVE']);
        Route::get("pacientes-obtener",[ReportesController::class,'pacientes']);
        Route::get("ultimos-pacientes-creados",[ReportesController::class,'MostrarUltimosPacientesCreados']);
        Route::get("ultimos-pacientes-modificados",[ReportesController::class,'MostrarUltimosPacientesModificados']);
        Route::post("consultas-obtener",[ReportesController::class,'getReporteMedico']);
        Route::post("generar-excel",[ReportesController::class,'generateExcel']);
        Route::get("obtener-pacientes/{id_medico}",[ReportesController::class,'obtenerPacientesPorMedicoYFecha']);
    })->middleware(EsAdministradorMiddleware::class)->middleware(['auth']);
    
    Route::group(['prefix'=>'estadisticos'],function(){
        Route::get("pacientes",[EstadisticosRepositorio::class,'obtenerPacientes']);
    })->middleware(EsAdministradorMiddleware::class)->middleware(['auth']);
});