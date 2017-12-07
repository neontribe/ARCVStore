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

// Service Registration Route
Route::get('/registraton', 'Service\RegistrationController@index')->name('service.registration');

// Default redirect to Service Dashboard
Route::get('/', function () {
    return redirect()->route('service.dashboard');
})->name('service.base');
