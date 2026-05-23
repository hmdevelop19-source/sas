<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Attendance routes
    Route::post('/attendances', [AttendanceController::class, 'store']);
    
    // Students API
    Route::get('/students', [\App\Http\Controllers\Api\StudentController::class, 'index']);
    Route::post('/students', [\App\Http\Controllers\Api\StudentController::class, 'store']);
    Route::get('/students/warnings', [\App\Http\Controllers\Api\StudentController::class, 'warnings']);

    // Utils
    Route::get('/utils/parse-nik', [\App\Http\Controllers\Api\NikController::class, 'parse']);

    // Region API
    Route::get('/regions/provinces', [\App\Http\Controllers\Api\RegionController::class, 'provinces']);
    Route::get('/regions/regencies/{provinceCode}', [\App\Http\Controllers\Api\RegionController::class, 'regencies']);
    Route::get('/regions/districts/{regencyCode}', [\App\Http\Controllers\Api\RegionController::class, 'districts']);
    Route::get('/regions/villages/{districtCode}', [\App\Http\Controllers\Api\RegionController::class, 'villages']);
    
    // Master Data API
    Route::get('/master/educations', [\App\Http\Controllers\Api\MasterDataController::class, 'educations']);
    Route::get('/master/occupations', [\App\Http\Controllers\Api\MasterDataController::class, 'occupations']);
    
    // Guardian API
    Route::get('/guardians', [\App\Http\Controllers\Api\GuardianController::class, 'index']);
    Route::get('/guardians/check', [\App\Http\Controllers\Api\GuardianController::class, 'check']);
});
