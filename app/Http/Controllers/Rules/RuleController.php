<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

use App\Models\Rules\Rule;
use App\Models\Rules\RuleVersion;
use App\Models\Rules\SalaryComponent;
use App\Models\Rules\RuleVersionComponent;

use App\Services\AuditLogger;

class RuleController extends Controller
{
    /**
     * Require DIRECTOR role for approve/reject.
     */
    public function index()
    {
        $rules = Rule::with(['latestVersion'])
            ->withCount('versions')
            ->whereHas('versions')
            ->orderByDesc(
                RuleVersion::select('created_at')
                    ->whereColumn('rule_id', 'rules.id')
                    ->latest('version')
                    ->limit(1)
            )
            ->paginate(10);

        return view('rules.repository.rules-repository', [
            'rules' => $rules,
            'type_menu' => 'rules',
        ]);
    }

    public function create()
    {
        $components = SalaryComponent::where('is_active', true)
            ->orderBy('component_code')
            ->get();

        return view('rules.repository.add-rules', [
            'components' => $components,
            'type_menu' => 'rules',
        ]);
    }

    public function evaluationReport()
    {
        $rules = Rule::with(['latestVersion'])
            ->withCount('versions')
            ->whereHas('versions')
            ->orderBy('name')
            ->get();

        $summary = [
            'total_rules' => $rules->count(),
            'total_versions' => RuleVersion::count(),
            'active_versions' => RuleVersion::where('status', RuleVersion::STATUS_ACTIVE)->count(),
            'draft_versions' => RuleVersion::where('status', RuleVersion::STATUS_DRAFT)->count(),
            'pending_approvals' => RuleVersion::where('approval_status', RuleVersion::APPROVAL_PENDING)->count(),
            'rejected_versions' => RuleVersion::where('approval_status', RuleVersion::APPROVAL_REJECTED)->count(),
        ];

        return view('rules.reports.evaluation-report', [
            'rules' => $rules,
            'summary' => $summary,
            'type_menu' => 'rules-evaluation-report',
        ]);
    }

    public function directorEvaluationReport()
    {
        $rules = Rule::with(['latestVersion'])
            ->withCount('versions')
            ->whereHas('versions')
            ->orderBy('name')
            ->get();

        $summary = [
            'total_rules' => $rules->count(),
            'total_versions' => RuleVersion::count(),
            'active_versions' => RuleVersion::where('status', RuleVersion::STATUS_ACTIVE)->count(),
            'draft_versions' => RuleVersion::where('status', RuleVersion::STATUS_DRAFT)->count(),
            'pending_approvals' => RuleVersion::where('approval_status', RuleVersion::APPROVAL_PENDING)->count(),
            'rejected_versions' => RuleVersion::where('approval_status', RuleVersion::APPROVAL_REJECTED)->count(),
        ];

        return view('rules.reports.director-evaluation-report', [
            'rules' => $rules,
            'summary' => $summary,
            'type_menu' => 'rules-evaluation-report-director',
        ]);
    }

    public function directorApprovalIndex()
    {
        $rules = Rule::with([
                'latestVersion',
                'versions' => function ($query) {
                    $query->where('approval_status', RuleVersion::APPROVAL_PENDING)
                        ->latest('version');
                },
            ])
            ->withCount('versions')
            ->withCount([
                'versions as pending_versions_count' => function ($query) {
                    $query->where('approval_status', RuleVersion::APPROVAL_PENDING);
                },
            ])
            ->whereHas('versions')
            ->orderBy('name')
            ->paginate(10, ['*'], 'rules_page');

        $pendingVersions = RuleVersion::with(['rule', 'salaryComponents'])
            ->where('approval_status', RuleVersion::APPROVAL_PENDING)
            ->latest('created_at')
            ->paginate(10);

        return view('rules.approval.index', [
            'rules' => $rules,
            'pendingVersions' => $pendingVersions,
            'type_menu' => 'rule-approval-director',
        ]);
    }

    public function directorApprovalShow(RuleVersion $ruleVersion)
    {
        $ruleVersion->load(['rule', 'salaryComponents']);

        return view('rules.approval.detail', [
            'ruleVersion' => $ruleVersion,
            'type_menu' => 'rule-approval-director',
        ]);
    }

    public function show(RuleVersion $ruleVersion)
    {
        $ruleVersion->load(['rule', 'salaryComponents']);

        $versions = RuleVersion::with('salaryComponents')
            ->where('rule_id', $ruleVersion->rule_id)
            ->latest('version')
            ->get();

        return view('rules.repository.rule-detail', [
            'ruleVersion' => $ruleVersion,
            'versions' => $versions,
            'type_menu' => 'rules',
        ]);
    }

    public function edit(RuleVersion $ruleVersion)
    {
        $ruleVersion->load(['rule', 'salaryComponents']);

        if ($ruleVersion->status !== RuleVersion::STATUS_DRAFT) {
            return redirect()
                ->route('hrd.rules.show', $ruleVersion)
                ->with('error', 'Hanya versi dengan status DRAFT yang dapat diedit.');
        }

        $components = SalaryComponent::where('is_active', true)
            ->orderBy('component_code')
            ->get();

        return view('rules.repository.edit-rule', [
            'ruleVersion' => $ruleVersion,
            'components' => $components,
            'type_menu' => 'rules',
        ]);
    }

    public function storeVersion(Request $request, RuleVersion $ruleVersion)
    {
        $ruleVersion->load(['rule', 'salaryComponents']);

        return DB::transaction(function () use ($request, $ruleVersion) {
            $nextVersionNumber = (int) RuleVersion::where('rule_id', $ruleVersion->rule_id)->max('version') + 1;

            $newVersion = RuleVersion::create([
                'rule_id' => $ruleVersion->rule_id,
                'version' => $nextVersionNumber,
                'status' => RuleVersion::STATUS_DRAFT,
                'approval_status' => null,
                'definition' => $ruleVersion->definition,
                'created_by' => $request->user()?->id,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => null,
                'rejected_at' => null,
                'decision_notes' => null,
                'activated_at' => null,
            ]);

            $componentIds = $ruleVersion->salaryComponents
                ->pluck('component_id')
                ->filter()
                ->unique()
                ->values();

            foreach ($componentIds as $componentId) {
                RuleVersionComponent::create([
                    'rule_version_id' => $newVersion->id,
                    'component_id' => $componentId,
                ]);
            }

            AuditLogger::log(
                $request,
                'RULE_VERSION_CREATE',
                $newVersion->id,
                null,
                [
                    'rule_id' => $ruleVersion->rule_id,
                    'source_rule_version_id' => $ruleVersion->id,
                    'version' => $newVersion->version,
                    'status' => $newVersion->status,
                    'approval_status' => $newVersion->approval_status,
                    'definition' => $newVersion->definition,
                    'component_ids' => $componentIds->all(),
                ],
                'Created new draft rule version from existing version'
            );

            return redirect()
                ->route('hrd.rules.edit', $newVersion)
                ->with('success', "Draft versi {$newVersion->version} berhasil dibuat. Silakan edit dulu sebelum diajukan.");
        });
    }

    public function updateVersion(Request $request, RuleVersion $ruleVersion): JsonResponse
    {
        if ($ruleVersion->status !== RuleVersion::STATUS_DRAFT) {
            abort(422, 'Hanya versi DRAFT yang dapat diedit.');
        }

        $submitAfterSave = filter_var($request->input('submit_after_save', false), FILTER_VALIDATE_BOOLEAN);
        $data = $this->validateRulePayload($request);
        $component = $this->findActiveComponent($data['definition']['action']['code']);

        return DB::transaction(function () use ($request, $ruleVersion, $data, $component, $submitAfterSave) {
            $before = $ruleVersion->load(['rule', 'salaryComponents'])->toArray();

            $ruleVersion->rule->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            $ruleVersion->update([
                'definition' => $data['definition'],
                'created_by' => $request->user()?->id ?? $ruleVersion->created_by,
                'approval_status' => $submitAfterSave ? RuleVersion::APPROVAL_PENDING : $ruleVersion->approval_status,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => null,
                'rejected_at' => null,
                'decision_notes' => null,
            ]);

            RuleVersionComponent::where('rule_version_id', $ruleVersion->id)->delete();

            RuleVersionComponent::create([
                'rule_version_id' => $ruleVersion->id,
                'component_id' => $component->component_id,
            ]);

            AuditLogger::log(
                $request,
                'RULE_VERSION_UPDATE',
                $ruleVersion->id,
                $before,
                $ruleVersion->fresh()->load(['rule', 'salaryComponents'])->toArray(),
                $submitAfterSave
                    ? 'Updated draft rule version and submitted for approval'
                    : 'Updated draft rule version'
            );

            return response()->json([
                'message' => $submitAfterSave
                    ? 'Perubahan berhasil disimpan dan diajukan untuk approval.'
                    : 'Draft versi berhasil disimpan.',
                'redirect_url' => route('hrd.rules.show', $ruleVersion),
                'rule_version_id' => $ruleVersion->id,
                'version' => $ruleVersion->version,
                'status' => $ruleVersion->status,
                'approval_status' => $ruleVersion->approval_status,
            ]);
        });
    }

    private function validateRulePayload(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'definition' => 'required|array',
            'definition.conditions' => 'required|array|min:1',
            'definition.action' => 'required|array',
            'definition.action.type' => 'required|string',
            'definition.action.code' => 'required|string|max:50',
            'definition.action.formula' => 'required|string',
        ]);

        $data['definition']['action']['code'] = strtoupper(trim($data['definition']['action']['code']));

        return $data;
    }

    private function findActiveComponent(string $actionCode): SalaryComponent
    {
        $component = SalaryComponent::where('component_code', $actionCode)
            ->where('is_active', true)
            ->first();

        if (!$component) {
            throw ValidationException::withMessages([
                'definition.action.code' => [
                    "Component code '{$actionCode}' tidak ditemukan di salary_components atau tidak aktif."
                ],
            ]);
        }

        return $component;
    }

    private function requireDirector(Request $request): void
    {
        $u = $request->user();
        $role = strtolower((string) ($u->role ?? ''));
        if (!$u || !in_array($role, ['director', 'direktur'], true)) {
            abort(403, 'Only DIRECTOR can approve/reject rules.');
        }
    }

    /**
     * CREATE rule + first version (DRAFT, approval PENDING).
     * Also maps the rule_version to a salary component based on action.code.
     */
    public function store(Request $request)
    {
        $data = $this->validateRulePayload($request);
        $component = $this->findActiveComponent($data['definition']['action']['code']);

        return DB::transaction(function () use ($request, $data, $component) {

            // 1) Create rule master
            $rule = Rule::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            // 2) Create first rule version and immediately submit it for director approval
            $version = RuleVersion::create([
                'rule_id' => $rule->id,
                'version' => 1,
                'status' => RuleVersion::STATUS_DRAFT,
                'approval_status' => RuleVersion::APPROVAL_PENDING,
                'definition' => $data['definition'],
                'created_by' => $request->user()?->id,
            ]);

            // 3) Map rule_version -> component_id
            RuleVersionComponent::create([
                'rule_version_id' => $version->id,
                'component_id' => $component->component_id,
            ]);

            // ===== AUDIT =====
            AuditLogger::log(
                $request,
                'RULE_CREATE',
                $version->id,
                null,
                ['rule_id' => $rule->id, 'rule_name' => $rule->name],
                "Created rule master"
            );

            AuditLogger::log(
                $request,
                'RULE_VERSION_CREATE',
                $version->id,
                null,
                [
                    'rule_id' => $rule->id,
                    'version' => $version->version,
                    'status' => $version->status,
                    'approval_status' => $version->approval_status,
                    'definition' => $version->definition,
                    'component_code' => $component->component_code,
                    'component_id' => $component->component_id,
                ],
                "Created first rule version and automatically submitted for approval"
            );

            return response()->json([
                'message' => 'Aturan berhasil disimpan dan otomatis diajukan ke direktur untuk approval',
                'rule_id' => $rule->id,
                'rule_version_id' => $version->id,
                'version' => $version->version,
                'status' => $version->status,
                'approval_status' => $version->approval_status,
                'component_code' => $component->component_code,
                'component_id' => $component->component_id,
            ], 201);
        });
    }

    /**
     * OPTIONAL: Submit a DRAFT rule version for approval.
     * Sets approval_status to PENDING and clears prior decision fields.
     */
    public function submitVersion(Request $request, RuleVersion $ruleVersion)
    {
        if ($ruleVersion->status !== RuleVersion::STATUS_DRAFT) {
            abort(422, 'Only DRAFT versions can be submitted.');
        }

        $before = $ruleVersion->toArray();

        $ruleVersion->update([
            'approval_status' => RuleVersion::APPROVAL_PENDING,
            'decision_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
            'rejected_by' => null,
            'rejected_at' => null,
        ]);

        AuditLogger::log(
            $request,
            'RULE_VERSION_SUBMIT',
            $ruleVersion->id,
            $before,
            $ruleVersion->fresh()->toArray(),
            'Submitted rule version for director approval'
        );

        return response()->json([
            'message' => 'Rule version submitted for approval',
            'rule_version' => $ruleVersion->fresh(),
        ]);
    }

    /**
     * DIRECTOR: Approve a rule version.
     */
    public function approveVersion(Request $request, RuleVersion $ruleVersion)
    {
        $this->requireDirector($request);

        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $before = $ruleVersion->toArray();

        $ruleVersion->update([
            'approval_status' => RuleVersion::APPROVAL_APPROVED,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'decision_notes' => $data['notes'] ?? null,
        ]);

        AuditLogger::log(
            $request,
            'RULE_VERSION_APPROVE',
            $ruleVersion->id,
            $before,
            $ruleVersion->fresh()->toArray(),
            'Director approved rule version'
        );

        return response()->json([
            'message' => 'Rule version approved',
            'rule_version' => $ruleVersion->fresh(),
        ]);
    }

    public function approveVersionWeb(Request $request, RuleVersion $ruleVersion)
    {
        $this->approveVersion($request, $ruleVersion);

        return redirect()
            ->route('rule-approval-detail-director', $ruleVersion)
            ->with('success', 'Rule version berhasil di-approve.');
    }

    /**
     * DIRECTOR: Reject a rule version (notes required).
     */
    public function rejectVersion(Request $request, RuleVersion $ruleVersion)
    {
        $this->requireDirector($request);

        $data = $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        $before = $ruleVersion->toArray();

        $ruleVersion->update([
            'approval_status' => RuleVersion::APPROVAL_REJECTED,
            'rejected_by' => $request->user()->id,
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
            'decision_notes' => $data['notes'],
        ]);

        AuditLogger::log(
            $request,
            'RULE_VERSION_REJECT',
            $ruleVersion->id,
            $before,
            $ruleVersion->fresh()->toArray(),
            'Director rejected rule version'
        );

        return response()->json([
            'message' => 'Rule version rejected',
            'rule_version' => $ruleVersion->fresh(),
        ]);
    }

    public function rejectVersionWeb(Request $request, RuleVersion $ruleVersion)
    {
        $this->rejectVersion($request, $ruleVersion);

        return redirect()
            ->route('rule-approval-detail-director', $ruleVersion)
            ->with('success', 'Rule version berhasil di-reject.');
    }

    /**
     * Activate a version of a rule.
     * Enforced by RuleVersion::activate() -> must be APPROVED.
     */
    public function activate(Request $request, Rule $rule, int $version)
    {
        $rv = RuleVersion::where('rule_id', $rule->id)
            ->where('version', $version)
            ->firstOrFail();

        $before = $rv->toArray();

        // This should throw if not approved
        $rv->activate();

        $after = $rv->fresh()->toArray();

        AuditLogger::log(
            $request,
            'RULE_VERSION_ACTIVATE',
            $rv->id,
            $before,
            $after,
            'Activated approved rule version'
        );

        return response()->json([
            'message' => 'Rule version activated',
            'rule_id' => $rule->id,
            'version' => $version,
            'rule_version' => $rv->fresh(),
        ]);
    }

    /**
     * Disable a rule version (set INACTIVE).
     */
    public function disableVersion(Request $request, RuleVersion $ruleVersion)
    {
        $before = $ruleVersion->toArray();

        if ($ruleVersion->status === RuleVersion::STATUS_ACTIVE) {
            $ruleVersion->update(['status' => RuleVersion::STATUS_INACTIVE]);
        }

        $after = $ruleVersion->fresh()->toArray();

        AuditLogger::log(
            $request,
            'RULE_VERSION_DISABLE',
            $ruleVersion->id,
            $before,
            $after,
            "Disabled rule version (set to INACTIVE)"
        );

        return response()->json([
            'message' => 'Rule version disabled (INACTIVE)',
            'rule_version' => $ruleVersion->fresh(),
        ]);
    }

    /**
     * Execute payroll rules:
     * - fetch ACTIVE rule_versions
     * - validate referenced components are active
     * - send to Go engine
     * - return components + summary
     */
    public function execute(Request $request)
    {
        $data = $request->validate([
            'facts' => 'required|array',
            'facts.employee' => 'required|array',
            'facts.attendance' => 'required|array',
            'facts.rates' => 'required|array',
        ]);

        $rules = RuleVersion::where('status', RuleVersion::STATUS_ACTIVE)->get();

        if ($rules->isEmpty()) {
            return response()->json([
                'message' => 'No ACTIVE rules found.',
                'sent' => [
                    'rules' => [],
                    'facts' => $data['facts'],
                ],
                'engine_response' => [
                    'components' => [],
                    'summary' => null,
                ],
            ], 200);
        }

        // Validate action.code exists & active in salary_components
        $codes = $rules->map(function ($rv) {
            $def = is_array($rv->definition) ? $rv->definition : [];
            $code = $def['action']['code'] ?? null;
            return is_string($code) ? strtoupper(trim($code)) : null;
        })->filter()->unique()->values();

        $activeCodes = SalaryComponent::whereIn('component_code', $codes)
            ->where('is_active', true)
            ->pluck('component_code')
            ->map(fn($x) => strtoupper(trim($x)))
            ->toArray();

        $missingOrInactive = $codes->diff($activeCodes)->values()->all();

        if (!empty($missingOrInactive)) {
            throw ValidationException::withMessages([
                'rules' => [
                    'Terdapat action.code yang tidak terdaftar atau tidak aktif pada salary_components: ' .
                        implode(', ', $missingOrInactive),
                ],
            ]);
        }

        $definitions = $rules->map(function ($rv) {
            $def = $rv->definition;
            if (isset($def['action']['code']) && is_string($def['action']['code'])) {
                $def['action']['code'] = strtoupper(trim($def['action']['code']));
            }
            return $def;
        })->values()->all();

        $payload = [
            'rules' => $definitions,
            'facts' => $data['facts'],
        ];

        $response = Http::post('http://localhost:8081/execute', $payload);

        AuditLogger::log(
            $request,
            'RULE_EXECUTE',
            null,
            ['sent' => $payload],
            ['engine_response' => $response->json(), 'status' => $response->status()],
            "Executed payroll rules"
        );

        return response()->json([
            'sent' => $payload,
            'engine_response' => $response->json(),
            'status' => $response->status(),
            'raw' => $response->body(),
        ], $response->successful() ? 200 : 500);
    }
}
