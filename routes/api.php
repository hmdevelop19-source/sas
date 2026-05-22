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
    
    // Students routes
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/warnings', [StudentController::class, 'warnings']);

    // Utils
    Route::get('/utils/parse-nik', [\App\Http\Controllers\Api\NikController::class, 'parse']);
});
