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

// Admin (Service) Authentication Routes...
Route::get('login', [
    'as' => 'service.login',
    'uses' => 'Service\Auth\LoginController@showLoginForm',
]);

Route::post('login', 'Service\Auth\LoginController@login');

Route::post('logout', [
    'as' => 'service.logout',
    'uses' => 'Service\Auth\LoginController@logout',
]);

// Admin (Service) Password Reset Routes...
Route::get('password/reset', 'Service\Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.request')
;
Route::post('password/email', 'Service\Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.email')
;
Route::get('password/reset/{token}', 'Service\Auth\ResetPasswordController@showResetForm')
    ->name('password.reset')
;
Route::post('password/reset', 'Service\Auth\ResetPasswordController@reset');

// Service Dashboard route
// Default redirect to Service Dashboard

// TODO : use of singular/ plurals in route names; Mixed opinions found. discuss.

Route::get('/', function () {
    return redirect()->route('service.dashboard');
})->name('service.base');

Route::group(['middleware' => 'auth:web'], function () {
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

    // Printables

    Route::put('/registration/{registration}/family', [
        'as' => 'service.registration.family.update',
        'uses' => 'Service\FamilyController@familyUpdate',
    ]);)
    // TODO : print feels... unRESTY; revise
    Route::get('/registration/{registration}/print', [
        'as' => 'service.registration.print',
        'uses' => 'Service\RegistrationController@print',
    ]);

    // TODO : print feels... unRESTY; revise
    Route::get('/centre/{centre}/registrations/print', [
        'as' => 'service.centre.registrations.print',
        'uses' => 'Service\CentreController@printRegistrations',
    ]);

    // Exportable reports
    Route::get('/centres/registrations/summary', [
        'as' => 'service.centres.registrations.summary',
        'uses' => 'Service\CentreController@exportRegistrationsSummary',
    ])->middleware(['can:export,App\Registration']);
});
