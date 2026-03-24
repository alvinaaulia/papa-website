@extends('layouts.app-hrd')

@section('title', 'Edit Draft Aturan')

@php
    $components =
        $components ?? \App\Models\Rules\SalaryComponent::where('is_active', true)->orderBy('component_code')->get();

    $initialRule = [
        'id' => $ruleVersion->id,
        'version' => $ruleVersion->version,
        'name' => $ruleVersion->rule?->name ?? '',
        'description' => $ruleVersion->rule?->description ?? '',
        'definition' => is_array($ruleVersion->definition) ? $ruleVersion->definition : [],
        'status' => $ruleVersion->status,
        'approval_status' => $ruleVersion->approval_status,
    ];
@endphp

@push('style')
    <style>
        .rule-wrapper {
            display: grid;
            grid-template-columns: 300px 1fr 340px;
            gap: 20px;
            align-items: stretch;
        }

        .rule-wrapper > * {
            height: 100%;
        }

        .panel {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .panel h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
            height: 100%;
        }

        .panel-column .panel {
            flex: 1;
        }

        .label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-control,
        .custom-select {
            width: 100%;
            height: 40px;
            border-radius: 10px !important;
            border: 1px solid #d1d5db !important;
            padding: 8px 12px;
        }

        textarea.form-control {
            min-height: 80px;
        }

        .form-control:focus,
        .custom-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
        }

        .muted {
            font-size: 12px;
            color: #6b7280;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 999px;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .rule-sentence-preview {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            font-size: 14px;
            line-height: 1.6;
            color: #1f2937;
        }

        .component-list {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            max-height: 260px;
            overflow-y: auto;
        }

        .component-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .component-card:last-child {
            border-bottom: none;
        }

        .component-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .component-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .component-info {
            display: flex;
            flex-direction: column;
        }

        .component-code {
            font-weight: 700;
            font-size: 13px;
            color: #4f46e5;
        }

        .component-name {
            font-size: 12px;
            color: #374151;
        }

        .result-card {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
            margin-top: 10px;
        }

        .ok {
            background: #ecfdf5;
            border-color: #bbf7d0;
        }

        .info {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .warn {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .result-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .editor-note {
            padding: 12px 14px;
            border-radius: 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 13px;
        }

        .existing-value {
            margin-top: 8px;
            padding: 9px 11px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        .existing-value b {
            color: #0f172a;
        }

        @media(max-width:1200px) {
            .rule-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                <div>
                    <h1 class="mb-0">Edit Draft Aturan</h1>
                    <div class="text-muted">
                        Aturan {{ $initialRule['name'] ?: '-' }} • Versi {{ $initialRule['version'] }}
                    </div>
                </div>

                <div class="d-flex flex-wrap" style="gap:10px;">
                    <a href="{{ route('hrd.rules.show', $ruleVersion) }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button id="btnSaveDraft" class="btn btn-light">
                        <i class="fas fa-save"></i> Simpan Draft
                    </button>
                    <button id="btnSubmitDraft" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Simpan & Ajukan Approval
                    </button>
                </div>
            </div>

            <div class="editor-note mb-3">
                Draft ini masih bisa Anda ubah. Approval baru diajukan saat menekan tombol
                <b>Simpan & Ajukan Approval</b>.
            </div>

            <div class="rule-wrapper">
                @include('rules.repository.partials.left-panel', [
                    'isEditMode' => true,
                    'initialRule' => $initialRule,
                ])
                @include('rules.repository.partials.center-panel', [
                    'components' => $components,
                    'isEditMode' => true,
                    'initialRule' => $initialRule,
                ])
                @include('rules.repository.partials.right-panel')
            </div>
        </section>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const initialRule = @json($initialRule);

            const conditionsRoot = document.getElementById('conditionsRoot');
            const definitionInput = document.getElementById('definitionJson');

            const ruleNameInput = document.getElementById('ruleName');
            const ruleDescriptionInput = document.getElementById('ruleDescription');
            const rulePriorityInput = document.getElementById('rulePriority');
            const effectiveDateInput = document.getElementById('effectiveDate');
            const endDateModeInput = document.getElementById('endDateMode');
            const endDateInput = document.getElementById('endDate');
            const actionCodeInput = document.getElementById('actionCode');
            const actionTypeInput = document.getElementById('actionType');
            const actionValueInput = document.getElementById('actionValue');

            const addRootConditionBtn = document.getElementById('addRootConditionBtn');
            const addRootGroupBtn = document.getElementById('addRootGroupBtn');
            const btnSaveDraft = document.getElementById('btnSaveDraft');
            const btnSubmitDraft = document.getElementById('btnSubmitDraft');
            const btnRunSim = document.getElementById('btnRunSim');

            function createConditionRow() {
                const template = document.getElementById('conditionRowTemplate');
                return template.content.firstElementChild.cloneNode(true);
            }

            function createGroup() {
                const template = document.getElementById('conditionGroupTemplate');
                return template.content.firstElementChild.cloneNode(true);
            }

            function appendCondition(targetContainer, data = null) {
                const row = createConditionRow();
                targetContainer.appendChild(row);
                bindRowEvents(row);

                if (data) {
                    row.querySelector('.field').value = data.field ?? 'employee.status';
                    row.querySelector('.operator').value = data.operator ?? '==';
                    row.querySelector('.value').value = Array.isArray(data.value) ? data.value.join(', ') : (data.value ?? '');
                }

                updatePreview();
            }

            function appendGroup(targetContainer, data = null) {
                const group = createGroup();
                targetContainer.appendChild(group);
                bindGroupEvents(group);

                if (data) {
                    group.querySelector('.group-type').value = data.type ?? 'AND';

                    const groupChildren = group.querySelector('.group-children');
                    (data.rules || []).forEach(child => {
                        hydrateNode(groupChildren, child);
                    });
                }

                updatePreview();
            }

            function hydrateNode(targetContainer, node) {
                if (node && Array.isArray(node.rules)) {
                    appendGroup(targetContainer, node);
                    return;
                }

                appendCondition(targetContainer, node);
            }

            function bindRowEvents(row) {
                row.querySelector('.remove-condition').addEventListener('click', function() {
                    row.remove();
                    updatePreview();
                });

                row.querySelectorAll('select, input').forEach(el => {
                    el.addEventListener('input', updatePreview);
                    el.addEventListener('change', updatePreview);
                });
            }

            function bindGroupEvents(group) {
                const childrenContainer = group.querySelector('.group-children');

                group.querySelector('.add-condition-inside').addEventListener('click', function() {
                    appendCondition(childrenContainer);
                });

                group.querySelector('.add-group-inside').addEventListener('click', function() {
                    appendGroup(childrenContainer);
                });

                group.querySelector('.remove-group').addEventListener('click', function() {
                    group.remove();
                    updatePreview();
                });

                group.querySelector('.group-type').addEventListener('change', updatePreview);
            }

            function parseValue(rawValue, operator) {
                const trimmed = String(rawValue ?? '').trim();

                if (operator === 'IN' || operator === 'NOT_IN') {
                    return trimmed
                        .split(',')
                        .map(item => item.trim())
                        .filter(item => item !== '');
                }

                if (trimmed === 'true') return true;
                if (trimmed === 'false') return false;
                if (trimmed === 'null') return null;

                if (trimmed !== '' && !isNaN(trimmed)) {
                    return Number(trimmed);
                }

                return trimmed;
            }

            function toggleEndDateInput() {
                const shouldShow = endDateModeInput.value === 'DATE';
                endDateInput.classList.toggle('d-none', !shouldShow);

                if (!shouldShow) {
                    endDateInput.value = '';
                }
            }

            const OPERATOR_TEXT = {
                "==": "sama dengan",
                "!=": "tidak sama dengan",
                ">": "lebih besar dari",
                "<": "lebih kecil dari",
                ">=": "lebih besar atau sama dengan",
                "<=": "lebih kecil atau sama dengan",
                "IN": "termasuk dalam",
                "NOT_IN": "tidak termasuk dalam",
                "CONTAINS": "mengandung"
            };

            function conditionToSentence(cond) {
                const field = cond.field;
                const operator = OPERATOR_TEXT[cond.operator] ?? cond.operator;
                const value = Array.isArray(cond.value) ? cond.value.join(", ") : cond.value;
                return `${field} ${operator} ${value}`;
            }

            function rulesToSentence(group) {
                if (!group || !group.rules) return "";
                const connector = group.type === "OR" ? "atau" : "dan";

                const parts = group.rules.map(rule => {
                    if (rule.rules) {
                        return "(" + rulesToSentence(rule) + ")";
                    }
                    return conditionToSentence(rule);
                });

                return parts.join(` ${connector} `);
            }

            function serializeContainer(container, type = 'AND') {
                const children = Array.from(container.children);
                const rules = [];

                children.forEach(child => {
                    if (child.classList.contains('condition-row')) {
                        const field = child.querySelector('.field').value;
                        const operator = child.querySelector('.operator').value;
                        const valueRaw = child.querySelector('.value').value;

                        rules.push({
                            field: field,
                            operator: operator,
                            value: parseValue(valueRaw, operator)
                        });
                    }

                    if (child.classList.contains('condition-group')) {
                        const groupType = child.querySelector('.group-type').value;
                        const groupChildren = child.querySelector('.group-children');

                        rules.push({
                            type: groupType,
                            rules: serializeContainer(groupChildren, groupType).rules
                        });
                    }
                });

                return {
                    type: type,
                    rules: rules
                };
            }

            function buildRulePayload() {
                return {
                    name: ruleNameInput.value.trim(),
                    definition: {
                        conditions: serializeContainer(conditionsRoot, 'AND'),
                        meta: {
                            description: ruleDescriptionInput.value.trim(),
                            priority: rulePriorityInput.value,
                            effective_date: effectiveDateInput.value || null,
                            end_date: endDateModeInput.value === 'DATE' ? (endDateInput.value || null) : null,
                        },
                        action: {
                            type: actionTypeInput.value,
                            code: actionCodeInput.value,
                            formula: actionValueInput.value.trim()
                        }
                    }
                };
            }

            function countValidLeafRules(group) {
                if (!group || !Array.isArray(group.rules)) return 0;

                let count = 0;

                group.rules.forEach(rule => {
                    if (Array.isArray(rule.rules)) {
                        count += countValidLeafRules(rule);
                        return;
                    }

                    const hasField = String(rule.field || '').trim() !== '';
                    const hasOperator = String(rule.operator || '').trim() !== '';
                    const hasValue = !(rule.value === '' || rule.value === undefined);

                    if (hasField && hasOperator && hasValue) {
                        count++;
                    }
                });

                return count;
            }

            function validatePayload(payload) {
                if (!payload.name) {
                    return 'Nama aturan wajib diisi.';
                }

                if (!payload.definition.action.code) {
                    return 'Komponen target wajib dipilih.';
                }

                if (!payload.definition.action.type) {
                    return 'Action type wajib dipilih.';
                }

                if (!payload.definition.action.formula) {
                    return 'Formula wajib diisi.';
                }

                const validRuleCount = countValidLeafRules(payload.definition.conditions);
                if (validRuleCount < 1) {
                    return 'Minimal harus ada 1 kondisi yang valid.';
                }

                return null;
            }

            function buildRuleSentence() {
                const payload = buildRulePayload();
                const cond = payload.definition.conditions;
                const action = payload.definition.action;
                const conditionSentence = rulesToSentence(cond);
                const actionSentence =
                    `${action.type.replaceAll("_"," ").toLowerCase()} komponen ${action.code} dengan formula ${action.formula}`;

                return `Jika ${conditionSentence} maka ${actionSentence}`;
            }

            function buildInitialRuleSentence() {
                const cond = initialRule.definition?.conditions;
                const action = initialRule.definition?.action || {};
                const conditionSentence = rulesToSentence(cond);
                const actionSentence =
                    `${String(action.type || '').replaceAll("_"," ").toLowerCase()} komponen ${action.code || '-'} dengan formula ${action.formula || '-'}`;

                return `Jika ${conditionSentence || '-'} maka ${actionSentence}`;
            }

            function formatStoredValue(value) {
                if (Array.isArray(value)) {
                    return value.join(', ');
                }

                if (value === null || value === undefined || value === '') {
                    return '-';
                }

                return String(value);
            }

            function fillExistingValue(id, value) {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = formatStoredValue(value);
                }
            }

            function updatePreview() {
                const payload = buildRulePayload();
                const ruleSentence = buildRuleSentence();

                document.getElementById("ruleSentencePreview").innerText = ruleSentence;
                definitionInput.value = JSON.stringify(payload.definition);

                const conditionCount = countValidLeafRules(payload.definition.conditions);
                const matchCountEl = document.getElementById('matchCount');
                const actionCountEl = document.getElementById('actionCount');

                if (matchCountEl) matchCountEl.innerText = conditionCount;
                if (actionCountEl) actionCountEl.innerText = payload.definition.action.formula ? '1' : '0';
            }

            async function submitDraft(submitAfterSave = false) {
                const payload = buildRulePayload();
                const error = validatePayload(payload);

                if (error) {
                    alert(error);
                    return;
                }

                const activeButton = submitAfterSave ? btnSubmitDraft : btnSaveDraft;
                btnSaveDraft.disabled = true;
                btnSubmitDraft.disabled = true;

                try {
                    const response = await fetch(`/api/hrd/rules/versions/${initialRule.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            ...payload,
                            submit_after_save: submitAfterSave
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        const message = result.message || Object.values(result.errors || {}).flat().join('\n') ||
                            'Gagal menyimpan draft aturan';
                        alert(message);
                        return;
                    }

                    alert(result.message || 'Draft berhasil disimpan');
                    window.location.href = result.redirect_url || `{{ route('hrd.rules.show', $ruleVersion) }}`;
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan saat menyimpan draft');
                } finally {
                    activeButton.disabled = false;
                    btnSaveDraft.disabled = false;
                    btnSubmitDraft.disabled = false;
                }
            }

            function hydrateForm() {
                const meta = initialRule.definition?.meta || {};

                ruleNameInput.value = initialRule.name || '';
                ruleDescriptionInput.value = meta.description || initialRule.description || '';
                rulePriorityInput.value = meta.priority || 'NORMAL';
                effectiveDateInput.value = meta.effective_date || '';
                endDateModeInput.value = meta.end_date ? 'DATE' : 'NONE';
                endDateInput.value = meta.end_date || '';
                toggleEndDateInput();

                const action = initialRule.definition?.action || {};
                actionCodeInput.value = action.code || '';
                actionTypeInput.value = action.type || 'SET_COMPONENT';
                actionValueInput.value = action.formula || '';

                conditionsRoot.innerHTML = '';

                const rootConditions = initialRule.definition?.conditions;

                if (rootConditions && Array.isArray(rootConditions.rules) && rootConditions.rules.length > 0) {
                    rootConditions.rules.forEach(node => {
                        hydrateNode(conditionsRoot, node);
                    });
                } else if (Array.isArray(rootConditions) && rootConditions.length > 0) {
                    rootConditions.forEach(node => {
                        hydrateNode(conditionsRoot, node);
                    });
                } else {
                    appendCondition(conditionsRoot);
                }

                fillExistingValue('existingRuleName', initialRule.name || '-');
                fillExistingValue('existingRuleDescription', meta.description || initialRule.description || '-');
                fillExistingValue('existingRulePriority', meta.priority || 'NORMAL');
                fillExistingValue('existingEffectiveDate', meta.effective_date || '-');
                fillExistingValue('existingEndDate', meta.end_date || 'Tidak ada batas');
                fillExistingValue('existingActionCode', action.code || '-');
                fillExistingValue('existingActionType', action.type || '-');
                fillExistingValue('existingActionFormula', action.formula || '-');
                fillExistingValue('existingConditionSummary', buildInitialRuleSentence());

                updatePreview();
            }

            addRootConditionBtn.addEventListener('click', function() {
                appendCondition(conditionsRoot);
            });

            addRootGroupBtn.addEventListener('click', function() {
                appendGroup(conditionsRoot);
            });

            [ruleNameInput, actionCodeInput, actionTypeInput, actionValueInput].forEach(el => {
                el.addEventListener('input', updatePreview);
                el.addEventListener('change', updatePreview);
            });

            [ruleDescriptionInput, rulePriorityInput, effectiveDateInput, endDateModeInput, endDateInput].forEach(el => {
                el?.addEventListener('input', updatePreview);
                el?.addEventListener('change', updatePreview);
            });

            endDateModeInput?.addEventListener('change', toggleEndDateInput);

            btnSaveDraft?.addEventListener('click', function() {
                submitDraft(false);
            });

            btnSubmitDraft?.addEventListener('click', function() {
                submitDraft(true);
            });

            btnRunSim?.addEventListener('click', function() {
                alert('Fitur simulasi belum dihubungkan ke backend.');
            });

            hydrateForm();
        });
    </script>
@endpush
