<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
   
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
    
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('qr/consulta/confirmacion', [AuthenticatedSessionController::class, 'MensajeConfirmacion']);

Route::get('qr/{paciente}/{folio}/{consulta}', function ($paciente, $folio, $consulta) {
    return Inertia::render('QR/VistaConfirmacion',
    [
        'paciente' => $paciente,
        'folio' => $folio,  
        'consulta' => $consulta,        
    ]);
})->name('nueva_consulta');