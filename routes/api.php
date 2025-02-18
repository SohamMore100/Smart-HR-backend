<?php

use App\Http\Controllers\EducationDetailsController;
use App\Http\Controllers\EmployeesDetailsController;
use App\Http\Controllers\ExperienceDetailsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    
    //user
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::post('/users', [AuthController::class, 'update']);
    Route::get('/users', [AuthController::class, 'index']);


    //education_details
    Route::get('/education', [EducationDetailsController::class, 'index']);
    Route::get('/education/{id}', [EducationDetailsController::class, 'show']);
    Route::post('/education/{id}', [EducationDetailsController::class, 'store']);
    Route::post('/education/{id}', [EducationDetailsController::class, 'update']);

    //employeed Details
    Route::get('/employees', [EmployeesDetailsController::class, 'index']);
    Route::get('/employees/{id}', [EmployeesDetailsController::class, 'show']);
    Route::post('/employees/{id}', [EmployeesDetailsController::class, 'store']);
    Route::post('/employees/{id}', [EmployeesDetailsController::class, 'update']);

    //Experience Details
    Route::get('/experience', [ExperienceDetailsController::class, 'index']);
    Route::get('/experience/{id}', [ExperienceDetailsController::class, 'show']);
    Route::post('/experience/{id}', [ExperienceDetailsController::class, 'store']);
    Route::post('/experience/{id}', [ExperienceDetailsController::class, 'update']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
