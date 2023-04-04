<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SocietyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/user', [SocietyController::class, 'user']);
    Route::post('/auth/login', [SocietyController::class, 'login']);
    Route::middleware('auth.custom')->group(function () {
        Route::post('/auth/logout', [SocietyController::class, 'logout']);
        Route::get('/consultations', [ConsultationController::class, 'index']);
        Route::post('/consultations', [ConsultationController::class, 'store']);
        Route::get('/spots', [RegionController::class, 'spots']);
        Route::get('/spots/{id}', [RegionController::class, 'showSpots']);
        Route::post('/vaccinations', [ConsultationController::class, 'storeVaccination']);
        Route::get('/vaccinations', [ConsultationController::class, 'indexVaccination']);
    });
});
