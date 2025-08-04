<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\YapeNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/taxis', function (Request $request) {
    return $request->user();
});

// Rutas públicas para dropdowns en cascada
Route::get('provinces/{department}', function ($departmentId) {
    return \App\Models\Tenant\Catalogs\Province::where('department_id', $departmentId)
        ->where('active', true)
        ->select('id', 'description')
        ->get();
});

Route::get('districts/{province}', function ($provinceId) {
    return \App\Models\Tenant\Catalogs\District::where('province_id', $provinceId)
        ->where('active', true)
        ->select('id', 'description')
        ->get();
});

// Grupo de rutas para notificaciones Yape
Route::prefix('yape')->group(function () {
    // Ruta principal para recibir notificaciones (la que usarás en Flutter)
    Route::post('/notifications', [YapeNotificationController::class, 'receiveNotification']);

    // Rutas adicionales para consultar datos
    Route::get('/notifications', [YapeNotificationController::class, 'getNotifications']);
    Route::get('/statistics', [YapeNotificationController::class, 'getStatistics']);
});
