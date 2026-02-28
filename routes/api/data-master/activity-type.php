<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

// direktur
Route::middleware('role:direktur,director')->group(function () {
    Route::get('activity-types', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'index']);
    Route::post('activity-types', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'store']);
    Route::post('activity-types/{activityTypesId}', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'update']);
    Route::delete('activity-types/{activityTypesId}', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'destroy']);
});
