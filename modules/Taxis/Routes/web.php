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
            Route::get('servicios/records', [ConductorController::class, 'serviciosRecords'])->name('taxis.conductor.servicios.records');

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
            Route::post('pagos/registrar', [PropietarioController::class, 'registrarPago'])->name('taxis.propietario.pagos.registrar');

            // Nuevas rutas para pagos con Yape
            Route::get('payment-configuration', [PropietarioController::class, 'getPaymentConfiguration'])->name('taxis.propietario.payment.configuration');
            Route::post('verificar-yape', [PropietarioController::class, 'verificarYape'])->name('taxis.propietario.verificar.yape');
            Route::post('confirmar-pago-yape', [PropietarioController::class, 'confirmarPagoYape'])->name('taxis.propietario.confirmar.pago.yape');

            // Ruta de prueba
            Route::get('test/vehiculos', [PropietarioController::class, 'testVehiculos'])->name('taxis.propietario.test.vehiculos');

            // Permisos del propietario
            Route::get('permisos', [PropietarioController::class, 'permisos'])->name('taxis.propietario.permisos');
            Route::get('permisos/records', [PropietarioController::class, 'permisosRecords'])->name('taxis.propietario.permisos.records');

            // Servicios del propietario
            Route::get('servicios', [PropietarioController::class, 'servicios'])->name('taxis.propietario.servicios');
            Route::get('servicios/records', [PropietarioController::class, 'serviciosRecords'])->name('taxis.propietario.servicios.records');

            // Perfil del propietario
            Route::get('perfil', [PropietarioController::class, 'perfil'])->name('taxis.propietario.perfil');
            Route::post('perfil', [PropietarioController::class, 'actualizarPerfil'])->name('taxis.propietario.perfil.update');

            // PDF Downloads
            Route::get('pdf/contrato/{vehiculo}', [PropietarioController::class, 'descargarContrato'])->name('taxis.propietario.pdf.contrato');
            Route::get('pdf/solicitud/{solicitud}', [PropietarioController::class, 'descargarSolicitud'])->name('taxis.propietario.pdf.solicitud');
            Route::get('pdf/constancia/{constancia}', [PropietarioController::class, 'descargarConstancia'])->name('taxis.propietario.pdf.constancia');
            Route::get('pdf/permiso/{permiso}', [PropietarioController::class, 'descargarPermiso'])->name('taxis.propietario.pdf.permiso');
        });
    });
});
