<?php

use App\Http\Controllers\Api\OvertimeController;
use Illuminate\Support\Facades\Route;

// karyawan
Route::middleware('role:karyawan')->group(function () {
    Route::get('/overtime-employee', [OvertimeController::class, 'employeeIndex']);
    Route::post('/overtime-submission-employee', [OvertimeController::class, 'employeeStore']);
});


// pm
Route::prefix('pm')->middleware('role:pm')->group(function () {
    Route::get('/overtimes', [OvertimeController::class, 'pmIndex']);
    Route::post('/overtimes', [OvertimeController::class, 'pmStore']);
});
// hrd
Route::prefix('hrd')->middleware('role:hrd')->group(function () {
    Route::get('/overtimes/approval-list', [OvertimeController::class, 'hrdApprovalList']);
    Route::post('/overtimes/{overtimeId}/approve', [OvertimeController::class, 'hrdApprove']);
    Route::post('/overtimes/{overtimeId}/reject', [OvertimeController::class, 'hrdReject']);
});

// direktur
Route::prefix('director')->middleware('role:direktur,director')->group(function () {
    Route::get('/overtimes/approval-list', [OvertimeController::class, 'directorApprovalList']);
    Route::post('/overtimes/{overtimeId}/approve', [OvertimeController::class, 'directorApprove']);
    Route::post('/overtimes/{overtimeId}/reject', [OvertimeController::class, 'directorReject']);
});
