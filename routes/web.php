<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return redirect()->route('companies.index');
    })->name('home');

    Route::get('/companies/{company}/logo', [CompanyController::class, 'logo'])->name('companies.logo');
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
});