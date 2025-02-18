<?php

use App\Http\Controllers\edu_details_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    
    //education_details
    Route::get('/education', [edu_details_Controller::class, 'index']);
    Route::get('/education/{id}', [edu_details_Controller::class, 'show']);
    Route::post('/education/{id}', [edu_details_Controller::class, 'store']);
    Route::post('/education/{id}', [edu_details_Controller::class, 'update']);

    //employeed Details
    Route::get('/employees', [emp_details_Controller::class, 'index']);
    Route::get('/employees/{id}', [emp_details_Controller::class, 'show']);
    Route::post('/employees/{id}', [emp_details_Controller::class, 'store']);
    Route::post('/employees/{id}', [emp_details_Controller::class, 'update']);

    //Experience Details
    Route::get('/experience', [exp_details_Controller::class, 'index']);
    Route::get('/experience/{id}', [exp_details_Controller::class, 'show']);
    Route::post('/experience/{id}', [exp_details_Controller::class, 'store']);
    Route::post('/experience/{id}', [exp_details_Controller::class, 'update']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
