<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('view_registrasi','PatientController@view_register');
Route::get('get_data_bayar','PatientController@get_data_bayar');
Route::get('get_jadwal_dokter','PatientController@get_jadwal_dokter');
Route::post('data_poli','PatientController@get_poli');
Route::post('save_register','PatientController@save_register');
Route::get('patient/get_patient/{id}', [App\Http\Controllers\PatientController::class, 'get_patient']);