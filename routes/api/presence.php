<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

// direktur
Route::middleware('role:direktur,director')->group(function () {
    Route::get('presence', [\App\Http\Controllers\Api\PresenceController::class, 'index']);
});

// hrd
Route::middleware('role:hrd')->group(function () {
    Route::get('presence-hrd', [\App\Http\Controllers\Api\PresenceController::class, 'getPresenceHRD']);
});

// pm — data presensi user yang login
Route::middleware('role:pm')->group(function () {
    Route::get('presence-pm', [\App\Http\Controllers\Api\PresenceController::class, 'getPresencePM']);
});

// karyawan — data presensi user yang login
Route::middleware('role:karyawan')->group(function () {
    Route::get('presence-karyawan', [\App\Http\Controllers\Api\PresenceController::class, 'getPresenceEmployee']);
});