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

// Service Dashboard route
Route::get('/dashboard', 'Service\DashboardController@index')->name('service.dashboard');

// Service Registration Routes
Route::get('/registration', 'Service\RegistrationController@index')->name('service.registration');
Route::post('/registration', 'Service\RegistrationController@store');
Route::put('/registration', 'Service\RegistrationController@update');

// Default redirect to Service Dashboard
Route::get('/', function () {
    return redirect()->route('service.dashboard');
})->name('service.base');
