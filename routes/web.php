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
    Route::get('/companies/{company}/logo', [CompanyController::class, 'logo'])->name('companies.logo');
    Route::get('/companies/options/select2', [CompanyController::class, 'options'])->name('companies.options.select2');
    Route::get('/employees/import-logs', [EmployeeController::class, 'importLogs'])->name('employees.import.logs');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::post('/employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');
    Route::resource('companies', CompanyController::class);
    Route::resource('employees', EmployeeController::class);
});