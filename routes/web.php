<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UnitsController as WebUnitsController;
use App\Http\Controllers\Web\TenantsController as WebTenantsController;
use App\Http\Controllers\Web\PaymentsController as WebPaymentsController;
use App\Http\Controllers\Web\UsersController as WebUsersController;
use App\Http\Controllers\Web\SettingsController as WebSettingsController;
use App\Http\Controllers\Web\PropertyFloorsController;


Route::get('/' , function () {
    return redirect()->route('admin.login');
});

// Auth Admin
Route::prefix('admin')->as('admin.')->controller(AuthController::class)->group(function () {
    Route::view('login', 'admin.auth.login')->name('login');
    Route::post('login', 'login')->name('login.attempt');
    Route::post('logout', 'logout')->name('logout');
});

// Admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('units', WebUnitsController::class);
    Route::get('units/data', [WebUnitsController::class, 'data'])->name('units.data');
    Route::resource('tenants', WebTenantsController::class);
    Route::get('tenants/data', [WebTenantsController::class, 'data'])->name('tenants.data');

    Route::resource('properties', \App\Http\Controllers\Web\PropertiesController::class);
    Route::post('properties/{property}/floors', [PropertyFloorsController::class, 'store'])->name('properties.floors.store');
    Route::put('properties/{property}/floors/{floor}', [PropertyFloorsController::class, 'update'])->name('properties.floors.update');
    Route::delete('properties/{property}/floors/{floor}', [PropertyFloorsController::class, 'destroy'])->name('properties.floors.destroy');
    Route::resource('contracts', \App\Http\Controllers\Web\ContractsController::class);
    Route::resource('contract-payments', \App\Http\Controllers\Web\ContractPaymentsController::class);
    Route::resource('owners', \App\Http\Controllers\Web\OwnersController::class);
    Route::resource('facilities', \App\Http\Controllers\Web\FacilitiesController::class);
    Route::resource('roles', \App\Http\Controllers\Web\RolesController::class)->except(['show']);
    
    Route::resource('expenses', \App\Http\Controllers\Web\ExpensesController::class);

    Route::resource('users', WebUsersController::class);
    Route::get('settings', [WebSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [WebSettingsController::class, 'update'])->name('settings.update');
});





