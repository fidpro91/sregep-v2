<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('apm/{any?}', function () {
    return view('app');
})->where('any', '.*');

Route::get('ms_unit/get_unit/{id?}','Ms_unitController@get_unit');
Route::post('ms_unit/update_data','Ms_unitController@update_data');
Route::get('ms_unit/get_dataTable','Ms_unitController@get_dataTable');
Route::resource('ms_unit', Ms_unitController::class);

Route::get('visit/get_dataTable','VisitController@get_dataTable');
Route::resource('visit', VisitController::class);

                Route::get('patient/patient_info/{type?}','PatientController@patient_info');
                Route::get('patient/get_patient/{type?}','PatientController@get_patient');
                Route::get('patient/get_dataTable','PatientController@get_dataTable');

                Route::resource('patient', PatientController::class);
                Route::get('ms_reff/get_dataTable','Ms_reffController@get_dataTable');
                Route::resource('ms_reff', Ms_reffController::class);
                Route::get('ms_surety/get_dataTable','Ms_suretyController@get_dataTable');
                Route::resource('ms_surety', Ms_suretyController::class);

                Route::get('ms_region/get_region_child/{id?}','Ms_regionController@get_region_child');
                Route::get('ms_region/get_region','Ms_regionController@get_region');
                Route::get('ms_region/get_dataTable','Ms_regionController@get_dataTable');
                Route::resource('ms_region', Ms_regionController::class);
                Route::get('ms_category_unit/get_dataTable','Ms_category_unitController@get_dataTable');
                Route::resource('ms_category_unit', Ms_category_unitController::class);
                Route::get('ms_perujuk/get_dataTable','Ms_perujukController@get_dataTable');
                Route::resource('ms_perujuk', Ms_perujukController::class);

                Route::get('employee/get_employee_unit/{unit_id?}','EmployeeController@get_employee_unit');
                Route::post('employee/get_schedule_medis','EmployeeController@get_schedule_medis');
                Route::get('employee/get_dataTable','EmployeeController@get_dataTable');
                Route::resource('employee', EmployeeController::class);

                Route::get('patient_surety/get_patient_surety/{px_id?}','Patient_suretyController@get_patient_surety');
                Route::get('patient_surety/get_dataTable','Patient_suretyController@get_dataTable');
                Route::resource('patient_surety', Patient_suretyController::class);
                Route::get('ms_menu/get_dataTable','Ms_menuController@get_dataTable');
                Route::resource('ms_menu', Ms_menuController::class);
                Route::get('employee_categories/get_dataTable','Employee_categoriesController@get_dataTable');
                Route::resource('employee_categories', Employee_categoriesController::class);
                Route::get('schedule_doctor_service/get_dataTable','Schedule_doctor_serviceController@get_dataTable');
                Route::resource('schedule_doctor_service', Schedule_doctor_serviceController::class);