<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\Rules\RuleAuditLog;

class AuditTrailController extends Controller
{
    public function index()
    {
        $logs = RuleAuditLog::with(['user', 'ruleVersion.rule'])
            ->orderByDesc('action_date')
            ->paginate(20);

        $summary = [
            'total_logs' => RuleAuditLog::count(),
            'today_logs' => RuleAuditLog::whereDate('action_date', today())->count(),
            'rule_logs' => RuleAuditLog::where('action_type', 'like', 'RULE%')->count(),
            'component_logs' => RuleAuditLog::where('action_type', 'like', 'COMPONENT%')->count(),
        ];

        return view('rules.audit.index', [
            'logs' => $logs,
            'summary' => $summary,
            'type_menu' => 'audit-trail',
        ]);
    }

    public function directorIndex()
    {
        $logs = RuleAuditLog::with(['user', 'ruleVersion.rule'])
            ->orderByDesc('action_date')
            ->paginate(20);

        $summary = [
            'total_logs' => RuleAuditLog::count(),
            'today_logs' => RuleAuditLog::whereDate('action_date', today())->count(),
            'rule_logs' => RuleAuditLog::where('action_type', 'like', 'RULE%')->count(),
            'component_logs' => RuleAuditLog::where('action_type', 'like', 'COMPONENT%')->count(),
        ];

        return view('rules.audit.director-index', [
            'logs' => $logs,
            'summary' => $summary,
            'type_menu' => 'audit-trail-director',
        ]);
    }

    public function show(RuleAuditLog $auditTrail)
    {
        $auditTrail->load(['user', 'ruleVersion.rule']);

        return view('rules.audit.detail', [
            'log' => $auditTrail,
            'type_menu' => 'audit-trail',
        ]);
    }

    public function directorShow(RuleAuditLog $auditTrail)
    {
        $auditTrail->load(['user', 'ruleVersion.rule']);

        return view('rules.audit.director-detail', [
            'log' => $auditTrail,
            'type_menu' => 'audit-trail-director',
        ]);
    }
}
