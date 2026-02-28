<?php

use App\Http\Controllers\Api\LeaveController;
use Illuminate\Support\Facades\Route;

// Karyawan: riwayat cuti & ajukan cuti
Route::middleware('role:karyawan')->group(function () {
    Route::get('/leave-employee', [LeaveController::class, 'employeeIndex']);
    Route::post('/leave-employee', [LeaveController::class, 'employeeStore']);
});

// PM: riwayat cuti, ajukan cuti, dan persetujuan cuti karyawan
Route::prefix('pm')->middleware('role:pm')->group(function () {
    Route::get('/leaves', [LeaveController::class, 'pmIndex']);
    Route::post('/leaves', [LeaveController::class, 'pmStore']);
    Route::get('/leaves/approval-list', [LeaveController::class, 'pmApprovalList']);
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'pmApprove']);
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'pmReject']);
});

// HRD: list pengajuan cuti & setujui/tolak
Route::prefix('hrd')->middleware('role:hrd')->group(function () {
    Route::get('/leaves', [LeaveController::class, 'hrdList']);
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'hrdApprove']);
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'hrdReject']);
});

// Direktur: list pengajuan cuti & setujui/tolak
Route::prefix('director')->middleware('role:direktur,director')->group(function () {
    Route::get('/leaves', [LeaveController::class, 'directorList']);
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'directorApprove']);
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'directorReject']);
});

// Detail cuti (semua role yang punya akses)
Route::get('/leaves/{id}', [LeaveController::class, 'show']);
