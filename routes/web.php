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

Route::resource('registration', 'Service\RegistrationController', [
        'names' => [
            'index' => 'service.registration.index',
            'create' => 'service.registration.create',
            'edit' => 'service.registration.edit',
            'store' => 'service.registration.store',
            'update' => 'service.registration.update',
        ],
        'only' => [
            'index',
            'create',
            'edit',
            'store',
            'update',
        ],
    ]);

// Printables TODO - these will one day be pdfs
Route::get('/registration/{registration}/print', [
    'as' => 'service.registration.print',
    'uses' => 'Service\RegistrationController@print',
]);

// TODO Not sure I got this right...
Route::get('/centre/{centre}/registrations/print', [
    'as' => 'service.centre.registrations.print',
    'uses' => 'Service\CentreController@printRegistrations',
]);

// Default redirect to Service Dashboard
Route::get('/', function () {
    return redirect()->route('service.dashboard');
})->name('service.base');
