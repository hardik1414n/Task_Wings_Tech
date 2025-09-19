<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
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

Route::middleware('auth:sanctum')->group( function () {
    Route::post('employee/create',[EmployeeController::class,'create']);
    Route::post('logout',[AuthController::class, 'logout']);
    Route::get('employees',[EmployeeController::class, 'employees']);
    Route::get('employees_edit/{id}',[EmployeeController::class, 'edit']);
    Route::delete('employee_delete/{id}',[EmployeeController::class, 'delete']);
    Route::post('employee_update/{id}',[EmployeeController::class, 'update']);
});

Route::post('login_check',[AuthController::class,'loginCheck'])->name('api.login.check');