<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API B2B
Route::post('/v1/auth/token', [\App\Http\Controllers\Api\AuthController::class, 'token'])
    ->middleware('throttle:6,1');

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Endpoints de Facturación
    Route::get('/documents', [\App\Http\Controllers\Api\DocumentController::class, 'index']);
    Route::post('/documents/send', [\App\Http\Controllers\Api\DocumentController::class, 'send']);
    Route::post('/documents/consult', [\App\Http\Controllers\Api\DocumentController::class, 'consult']);
    Route::post('/documents/reintentar', [\App\Http\Controllers\Api\DocumentController::class, 'retry']);
    
    // Endpoints de Gestión de Empresas
    Route::get('/empresas', [\App\Http\Controllers\Api\CompanyController::class, 'index']);
    Route::post('/empresa/crear', [\App\Http\Controllers\Api\CompanyController::class, 'store']);
    Route::post('/empresa/produccion', [\App\Http\Controllers\Api\CompanyController::class, 'toProduction']);
    Route::post('/empresa/certificado', [\App\Http\Controllers\Api\CompanyController::class, 'uploadCertificate']);
    Route::delete('/empresa/{ruc}', [\App\Http\Controllers\Api\CompanyController::class, 'destroy']);
    
    // Agencia
    Route::get('/saldo', [\App\Http\Controllers\Api\AgencyController::class, 'saldo']);
});
