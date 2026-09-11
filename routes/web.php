<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/docs', 'docs.endpoints.auth-token')->name('docs');
Route::view('/docs/authentication', 'docs.endpoints.auth-token')->name('docs.authentication');
Route::view('/docs/documents/send', 'docs.endpoints.documents-send')->name('docs.documents.send');
Route::view('/docs/documents/void', 'docs.endpoints.documents-void')->name('docs.documents.void');
Route::view('/docs/documents/modelos', 'docs.endpoints.models')->name('docs.documents.models');
Route::view('/docs/documents/consult', 'docs.endpoints.documents-consult')->name('docs.documents.consult');
Route::view('/docs/documents/reintentar', 'docs.endpoints.documents-reintentar')->name('docs.documents.reintentar');
Route::view('/docs/empresas', 'docs.endpoints.empresas-index')->name('docs.empresas.index');
Route::view('/docs/empresa/crear', 'docs.endpoints.empresa-crear')->name('docs.empresa.crear');
Route::view('/docs/empresa/produccion', 'docs.endpoints.empresa-produccion')->name('docs.empresa.produccion');
Route::view('/docs/empresa/certificado', 'docs.endpoints.empresa-certificado')->name('docs.empresa.certificado');
Route::view('/docs/empresa/eliminar', 'docs.endpoints.empresa-eliminar')->name('docs.empresa.eliminar');
Route::view('/docs/saldo', 'docs.endpoints.saldo')->name('docs.saldo');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::post('companies/{company}/certificate', [\App\Http\Controllers\CertificateController::class, 'store'])->name('certificates.store');
    Route::get('/documents', [\App\Http\Controllers\Web\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/summaries', [\App\Http\Controllers\Web\SummaryController::class, 'index'])->name('summaries.index');
    Route::post('/api-keys', [\App\Http\Controllers\ApiKeyController::class, 'store'])->name('api-keys.store');
    Route::delete('/api-keys/{id}', [\App\Http\Controllers\ApiKeyController::class, 'destroy'])->name('api-keys.destroy');

    // Admin Routes
    Route::get('/admin/agencies', [\App\Http\Controllers\Admin\AgencyController::class, 'index'])->name('admin.agencies');
    Route::post('/admin/agencies/{agency}/balance', [\App\Http\Controllers\Admin\AgencyController::class, 'addBalance'])->name('admin.agencies.balance');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
