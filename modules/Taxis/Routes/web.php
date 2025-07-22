<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Taxis\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['check.permission', 'locked.tenant'])->prefix('taxis')->group(function () {

    // Rutas de autenticación (sin middleware adicional)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('taxis.login');
    Route::post('login', [AuthController::class, 'login'])->name('taxis.login.post');

    // Rutas protegidas con middleware de autenticación de taxis
    Route::middleware(['taxis.auth'])->group(function () {
        // Dashboard principal
        Route::get('/', [AuthController::class, 'dashboard'])->name('taxis.dashboard');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('taxis.dashboard.alt');

        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('taxis.logout');

        // API para obtener usuario autenticado
        Route::get('api/user', [AuthController::class, 'user'])->name('taxis.api.user');

        // Rutas adicionales del módulo
        Route::get('profile', function () {
            return view('taxis::profile');
        })->name('taxis.profile');

        // Módulos Principales del Sistema de Taxis
        // Vehículos
        Route::get('vehiculos', function () {
            return view('taxis::vehiculos.index');
        })->name('taxis.vehiculos.index');
        Route::get('vehiculos/create', function () {
            return view('taxis::vehiculos.create');
        })->name('taxis.vehiculos.create');
        Route::get('vehiculos/{id}', function ($id) {
            return view('taxis::vehiculos.show', compact('id'));
        })->name('taxis.vehiculos.show');
        Route::get('vehiculos/{id}/edit', function ($id) {
            return view('taxis::vehiculos.edit', compact('id'));
        })->name('taxis.vehiculos.edit');

        // Pagos
        Route::get('pagos', function () {
            return view('taxis::pagos.index');
        })->name('taxis.pagos.index');
        Route::get('pagos/create', function () {
            return view('taxis::pagos.create');
        })->name('taxis.pagos.create');
        Route::get('pagos/{id}', function ($id) {
            return view('taxis::pagos.show', compact('id'));
        })->name('taxis.pagos.show');

        // Contratos
        Route::get('contratos', function () {
            return view('taxis::contratos.index');
        })->name('taxis.contratos.index');
        Route::get('contratos/create', function () {
            return view('taxis::contratos.create');
        })->name('taxis.contratos.create');
        Route::get('contratos/{id}', function ($id) {
            return view('taxis::contratos.show', compact('id'));
        })->name('taxis.contratos.show');

        // Solicitudes
        Route::get('solicitudes', function () {
            return view('taxis::solicitudes.index');
        })->name('taxis.solicitudes.index');
        Route::get('solicitudes/create', function () {
            return view('taxis::solicitudes.create');
        })->name('taxis.solicitudes.create');
        Route::get('solicitudes/{id}', function ($id) {
            return view('taxis::solicitudes.show', compact('id'));
        })->name('taxis.solicitudes.show');

        // Documentación y Permisos
        // Condiciones
        Route::get('condiciones', function () {
            return view('taxis::condiciones.index');
        })->name('taxis.condiciones.index');
        Route::get('condiciones/create', function () {
            return view('taxis::condiciones.create');
        })->name('taxis.condiciones.create');
        Route::get('condiciones/{id}', function ($id) {
            return view('taxis::condiciones.show', compact('id'));
        })->name('taxis.condiciones.show');

        // Constancias
        Route::get('constancias', function () {
            return view('taxis::constancias.index');
        })->name('taxis.constancias.index');
        Route::get('constancias/create', function () {
            return view('taxis::constancias.create');
        })->name('taxis.constancias.create');
        Route::get('constancias/{id}', function ($id) {
            return view('taxis::constancias.show', compact('id'));
        })->name('taxis.constancias.show');

        // Permisos
        Route::get('permisos', function () {
            return view('taxis::permisos.index');
        })->name('taxis.permisos.index');
        Route::get('permisos/create', function () {
            return view('taxis::permisos.create');
        })->name('taxis.permisos.create');
        Route::get('permisos/{id}', function ($id) {
            return view('taxis::permisos.show', compact('id'));
        })->name('taxis.permisos.show');
    });
});
