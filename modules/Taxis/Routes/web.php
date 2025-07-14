<?php



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
    Route::get('/', 'TaxisController@index');
    Route::get('login', 'TaxisController@showLogin');
    Route::post('login', 'TaxisController@login');
});
