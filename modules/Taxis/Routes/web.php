<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Taxis\AuthController;
use Modules\Taxis\Http\Controllers\ConductorController;
use Modules\Taxis\Http\Controllers\PropietarioController;

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

        // Rutas para CONDUCTORES
        Route::middleware(['taxis.conductor'])->prefix('conductor')->group(function () {
            Route::get('dashboard', [ConductorController::class, 'dashboard'])->name('taxis.conductor.dashboard');
            Route::get('vehiculo', [ConductorController::class, 'vehiculo'])->name('taxis.conductor.vehiculo');

            // Permisos del conductor
            Route::get('permisos', [ConductorController::class, 'permisos'])->name('taxis.conductor.permisos');
            Route::get('permisos/records', [ConductorController::class, 'permisosRecords'])->name('taxis.conductor.permisos.records');

            // Pagos del conductor
            Route::get('pagos', [ConductorController::class, 'pagos'])->name('taxis.conductor.pagos');
            Route::get('pagos/records', [ConductorController::class, 'pagosRecords'])->name('taxis.conductor.pagos.records');

            // Servicios del conductor
            Route::get('servicios', [ConductorController::class, 'servicios'])->name('taxis.conductor.servicios');

            // Perfil del conductor
            Route::get('perfil', [ConductorController::class, 'perfil'])->name('taxis.conductor.perfil');
            Route::post('perfil', [ConductorController::class, 'actualizarPerfil'])->name('taxis.conductor.perfil.update');
        });

        // Rutas para PROPIETARIOS
        Route::middleware(['taxis.propietario'])->prefix('propietario')->group(function () {
            Route::get('dashboard', [PropietarioController::class, 'dashboard'])->name('taxis.propietario.dashboard');

            // Vehículos del propietario
            Route::get('vehiculos', [PropietarioController::class, 'vehiculos'])->name('taxis.propietario.vehiculos');
            Route::get('vehiculos/records', [PropietarioController::class, 'vehiculosRecords'])->name('taxis.propietario.vehiculos.records');
            Route::get('vehiculos/{id}', [PropietarioController::class, 'vehiculoDetalle'])->name('taxis.propietario.vehiculos.show');

            // Conductores del propietario
            Route::get('conductores', [PropietarioController::class, 'conductores'])->name('taxis.propietario.conductores');
            Route::get('conductores/records', [PropietarioController::class, 'conductoresRecords'])->name('taxis.propietario.conductores.records');

            // Contratos del propietario
            Route::get('contratos', [PropietarioController::class, 'contratos'])->name('taxis.propietario.contratos');
            Route::get('contratos/records', [PropietarioController::class, 'contratosRecords'])->name('taxis.propietario.contratos.records');

            // Solicitudes del propietario
            Route::get('solicitudes', [PropietarioController::class, 'solicitudes'])->name('taxis.propietario.solicitudes');
            Route::get('solicitudes/records', [PropietarioController::class, 'solicitudesRecords'])->name('taxis.propietario.solicitudes.records');

            // Constancias del propietario
            Route::get('constancias', [PropietarioController::class, 'constancias'])->name('taxis.propietario.constancias');
            Route::get('constancias/records', [PropietarioController::class, 'constanciasRecords'])->name('taxis.propietario.constancias.records');

            // Pagos del propietario
            Route::get('pagos', [PropietarioController::class, 'pagos'])->name('taxis.propietario.pagos');
            Route::get('pagos/records', [PropietarioController::class, 'pagosRecords'])->name('taxis.propietario.pagos.records');

            // Permisos del propietario
            Route::get('permisos', [PropietarioController::class, 'permisos'])->name('taxis.propietario.permisos');
            Route::get('permisos/records', [PropietarioController::class, 'permisosRecords'])->name('taxis.propietario.permisos.records');

            // Servicios del propietario
            Route::get('servicios', [PropietarioController::class, 'servicios'])->name('taxis.propietario.servicios');

            // Perfil del propietario
            Route::get('perfil', [PropietarioController::class, 'perfil'])->name('taxis.propietario.perfil');
            Route::post('perfil', [PropietarioController::class, 'actualizarPerfil'])->name('taxis.propietario.perfil.update');
        });

        // Rutas adicionales del módulo (mantenidas para compatibilidad)
        Route::get('profile', function () {
            return view('taxis::profile');
        })->name('taxis.profile');

        // Módulos Principales del Sistema de Taxis (rutas heredadas - deprecar gradualmente)
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
