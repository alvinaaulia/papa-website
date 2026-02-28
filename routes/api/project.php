<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

// direktur
Route::middleware('role:direktur,director')->group(function () {
    Route::get('projects', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'index']);
    Route::post('projects', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'store']);
    Route::get('projects/{projectId}', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'show']);
});
