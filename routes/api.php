<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API B2B
Route::post('/v1/auth/token', [\App\Http\Controllers\Api\AuthController::class, 'token']);

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Endpoints de Facturación
    Route::post('/documents/send', [\App\Http\Controllers\Api\DocumentController::class, 'send']);
    Route::post('/documents/consult', [\App\Http\Controllers\Api\DocumentController::class, 'consult']);
    
    // Endpoints de Gestión de Empresas
    Route::get('/empresas', [\App\Http\Controllers\Api\CompanyController::class, 'index']);
    Route::post('/empresa/crear', [\App\Http\Controllers\Api\CompanyController::class, 'store']);
    Route::post('/empresa/produccion', [\App\Http\Controllers\Api\CompanyController::class, 'toProduction']);
});
