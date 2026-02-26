<?php

use App\Http\Controllers\Api\DataMaster\MasterPositionController;
use App\Http\Controllers\Api\DataMaster\UserController;
use App\Http\Controllers\Api\DataMaster\SalaryController;
use App\Http\Controllers\Api\DataMaster\MasterSalaryController;
use App\Http\Controllers\Api\DataMaster\PositionController;
use App\Http\Controllers\Api\DataMaster\MasterLeaveTypeController;
use App\Http\Controllers\Api\DataMaster\MasterDocumentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('employees', [UserController::class, 'index']);
Route::post('employees/add-employees', [UserController::class, 'store']);
Route::get('employees/show-employees/{UserId}', [UserController::class, 'show']);

Route::get('activity-types', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'index']);
Route::post('activity-types', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'store']);
Route::post('activity-types/{activityTypesId}', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'update']);
Route::delete('activity-types/{activityTypesId}', [\App\Http\Controllers\Api\DataMaster\ActivityTypeContoller::class, 'destroy']);

// api.php
Route::prefix('salary')->group(function () {
    Route::get('/', [SalaryController::class, 'index']);
    Route::post('/', [SalaryController::class, 'store']);
    Route::get('/user/{id}', [SalaryController::class, 'getByUser']);
    Route::post('/{id}/update-proof', [SalaryController::class, 'updateTransferProof']);
    Route::get('/history', [SalaryController::class, 'getSalaryHistory']);
    Route::get('/detail/{id}', [SalaryController::class, 'getSalaryDetail']);
    Route::delete('/{id}', [SalaryController::class, 'destroy']);
});

Route::prefix('master-salary')->group(function () {
    Route::get('/', [MasterSalaryController::class, 'index']);
    Route::post('/', [MasterSalaryController::class, 'store']);
    Route::get('/user/{id}', [MasterSalaryController::class, 'getByUser']);
    Route::get('/users', [MasterSalaryController::class, 'getAllUsers']);
    Route::post('/{id_master_salary}', [MasterSalaryController::class, 'update']);
    Route::delete('/{id}', [MasterSalaryController::class, 'destroy']);
});

Route::prefix('master-positions')->group(function () {
    Route::get('/', [MasterPositionController::class, 'index']);
    Route::post('/', [MasterPositionController::class, 'store']);
    Route::get('/{id}', [MasterPositionController::class, 'show']);
    Route::post('/{id}', [MasterPositionController::class, 'update']);
    Route::delete('/{id}', [MasterPositionController::class, 'destroy']);
});

Route::prefix('positions')->group(function () {
    Route::get('/', [PositionController::class, 'index']);
    Route::post('/', [PositionController::class, 'store']);
    Route::get('/{id}', [PositionController::class, 'show']);
    Route::put('/{id}', [PositionController::class, 'update']);
    Route::delete('/{id}', [PositionController::class, 'destroy']);
});

Route::prefix('leave-type')->group(function(){
    Route::get('/', [MasterLeaveTypeController::class, 'index']);
});

Route::prefix('master-document')->group(function(){
    Route::get('/', [MasterDocumentController::class, 'index']);
    Route::post('/', [MasterDocumentController::class, 'store']);
    Route::post('/{id}', [MasterDocumentController::class, 'update']);
    Route::delete('/{id}', [MasterDocumentController::class, 'destroy']);
});
