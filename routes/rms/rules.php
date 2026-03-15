<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rules\RuleController;
use App\Http\Controllers\Rules\SalaryComponentController;
use App\Models\Rules\RuleAuditLog;

Route::middleware('auth:sanctum')->get('/me', function (\Illuminate\Http\Request $request) {
    return $request->user();
});

Route::prefix('hrd')->middleware('auth:sanctum', 'role:hrd')->group(function () {
    Route::post('/rules', [RuleController::class, 'store']);
    Route::get('/rules', [RuleController::class, 'index'])->name('index');
    Route::get('/rules/create', [RuleController::class, 'create']);
    Route::post('/rules/{rule}/activate/{version}', [RuleController::class, 'activate']);
    Route::post('/payroll/run', [RuleController::class, 'execute']);

    Route::get('/components', [SalaryComponentController::class, 'index']);
    Route::post('/components', [SalaryComponentController::class, 'store']);
    Route::get('/components/{component}', [SalaryComponentController::class, 'show']);
    Route::put('/components/{component}', [SalaryComponentController::class, 'update']);
    Route::post('/components/{component}/activate', [SalaryComponentController::class, 'activate']);
    Route::post('/components/{component}/disable', [SalaryComponentController::class, 'disable']);

    Route::post('/rules/versions/{ruleVersion}/submit', [RuleController::class, 'submitVersion']);
    Route::post('/rules/versions/{ruleVersion}/approve', [RuleController::class, 'approveVersion']);
    Route::post('/rules/versions/{ruleVersion}/reject', [RuleController::class, 'rejectVersion']);

    Route::get('/audit-logs', function () {
        return RuleAuditLog::orderByDesc('audit_log_id')->limit(200)->get();
    });
});
