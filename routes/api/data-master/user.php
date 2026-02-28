<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\DataMaster\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('data-master')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users/add-users', [UserController::class, 'store']);
    Route::get('users/show-users/{UserId}', [UserController::class, 'show']);
    Route::delete('users/{UserId}', [UserController::class, 'destroy']);
});
