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
Route::get('/registration/create', 'Service\RegistrationController@create')->name('service.create_registration');
Route::post('/registration', 'Service\RegistrationController@store');

// Get edit form for a specific Registration by ID.
Route::get('/registration/{id}/edit', 'Service\RegistrationController@edit')->name('service.edit_registration');

// Default redirect to Service Dashboard
Route::get('/', function () {
    return redirect()->route('service.dashboard');
})->name('service.base');
