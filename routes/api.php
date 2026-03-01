<?php

use App\Http\Controllers\Api\DailyActivityController;
use App\Http\Controllers\Api\DailyActivityProjectController;
use App\Http\Controllers\Api\DataMaster\ContractController;
use App\Http\Controllers\Api\DataMaster\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataMaster\MasterPositionController;
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

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('web');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('web');

    require __DIR__ . '/api/overtime.php';

    require __DIR__ . '/api/leave.php';

    Route::get('daily-activities-director', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'dailyActivitiesDirector']);
    
    // HRD daily activities
    Route::get('list-projects-hrd', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'getDailyActivities']);
    Route::get('detail-projects-hrd/{projectId}', [\App\Http\Controllers\Api\DataMaster\ProjectController::class, 'DetailDailyActivities']);
    
    // hrd
    Route::get('contracts', [ContractController::class, 'index']);
    Route::post('contracts', [ContractController::class, 'store']);
    
    // employee/hrd/direktur
    Route::get('contracts/{contractId}', [ContractController::class, 'show']);
    
    // contract direktur
    Route::get('contracts/approval-list', [ContractController::class, 'approvalList']);
    Route::post('/employee/contracts/{contractId}/approve-director', [ContractController::class, 'approvalContractDirector']);
    Route::post('/pm/contracts/{contractId}/approve-director', [ContractController::class, 'approvalContractDirector']);
    
    // contract employee
    Route::get('/employee/contracts', [ContractController::class, 'employeeContractList']);
    Route::get('/employee/contracts/{contractId}', [ContractController::class, 'employeeContractDetail']);
    Route::post('/employee/contracts/{contractId}/approve-employee', [ContractController::class, 'approvalContract']);
    
    // contract pm
    Route::get('/pm/contracts', [ContractController::class, 'ContractListPM']);
    Route::post('/pm/contracts/{contractId}/approve-pm', [ContractController::class, 'approvalContractPM']);
    Route::get('/pm/contracts/{contractId}', [ContractController::class, 'ContractDetailPM']);
    
    
    // hrd
    Route::prefix('hrd')->group(function () {
        Route::get('/contracts', [ContractController::class, 'index']);
        Route::get('/contracts/filter', [ContractController::class, 'filterByStatus']);
        Route::get('/contracts/{contractId}', [ContractController::class, 'show']);
        Route::post('/contracts', [ContractController::class, 'store']);
        Route::put('/contracts/{contractId}', [ContractController::class, 'update']);
    });
    
    //employee daily activities
    Route::get('employee/daily-activities', [\App\Http\Controllers\Api\DailyActivityController::class, 'EmployeeListDaily']);
    Route::get('employee/daily-activities/{id}', [\App\Http\Controllers\Api\DailyActivityController::class, 'EmployeeDailyDetail']);
    
    // pm
    Route::get('daily-activities-pm', [\App\Http\Controllers\Api\DailyActivityController::class, 'index']);
    Route::post('daily-activities-pm', [\App\Http\Controllers\Api\DailyActivityController::class, 'store']);
    Route::get('daily-activities-pm/{id}', [\App\Http\Controllers\Api\DailyActivityController::class, 'show']);
    
    // pm
    Route::get('tasks', [\App\Http\Controllers\Api\TaskController::class, 'index']);
    Route::post('tasks', [\App\Http\Controllers\Api\TaskController::class, 'store']);
    
    //direktur daily activity PDF
    Route::get('/generate-pdf', [DailyActivityProjectController::class, 'generatePDF']);
    Route::get('/report/data', [DailyActivityProjectController::class, 'getReportData']);
    
    // mengambil route api presence di folder api
    require __DIR__ . '/api/presence.php';
    
    // mengambil route api project di folder api
    require __DIR__ . '/api/project.php';
    
    // mengambil route api activity-type di folder api
    require __DIR__ . '/api/data-master/activity-type.php';
    
    // mengambil route api user di folder api/DataMaster
    require __DIR__ . '/api/data-master/user.php';
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
