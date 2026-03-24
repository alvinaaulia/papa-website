<div class="rule-center-panel">

    {{-- TARGET COMPONENT --}}
    <div class="panel mb-3">
        <div class="block-head">
            <span class="tag">TARGET</span>
            <span class="title">Komponen Target</span>
            <span class="badge-wajib">Wajib</span>
        </div>

        <select id="actionCode" class="custom-select" name="action_code">
            <option value="">-- pilih komponen --</option>
            @foreach ($components as $component)
                <option value="{{ $component->component_code }}">
                    {{ $component->component_code }} — {{ $component->component_name }}
                </option>
            @endforeach
        </select>
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b>
                <span id="existingActionCode">{{ data_get($initialRule, 'definition.action.code', '-') }}</span>
            </div>
        @endif

        <div class="muted mt-2">
            Komponen yang akan dipengaruhi oleh rule
        </div>
    </div>

    {{-- CONDITIONS --}}
    <div class="panel mb-3">
        <div class="block-head">
            <span class="tag">IF</span>
            <span class="title">Conditions Builder</span>
            <span class="badge-wajib">Wajib</span>
        </div>

        <div class="builder-toolbar mb-3">
            <button type="button" id="addRootConditionBtn" class="btn-builder">
                <i class="fas fa-plus"></i> Tambah Kondisi
            </button>

            <button type="button" id="addRootGroupBtn" class="btn-builder btn-builder-secondary">
                <i class="fas fa-project-diagram"></i> Tambah Group
            </button>
        </div>
        @if (!empty($isEditMode))
            <div class="existing-value mb-3">
                <b>Rule saat ini:</b>
                <span id="existingConditionSummary">-</span>
            </div>
        @endif

        <div id="conditionsRoot" class="condition-root"></div>

        <div class="muted mt-2">
            Susun rule dengan kondisi tunggal atau group AND/OR bertingkat
        </div>
    </div>

    {{-- ACTION --}}
    <div class="panel mb-3">
        <div class="block-head">
            <span class="tag">THEN</span>
            <span class="title">Action / Formula</span>
            <span class="badge-wajib">Wajib</span>
        </div>

        <select id="actionType" class="custom-select mb-2" name="action_type">
            <option value="SET_COMPONENT">SET COMPONENT</option>
            <option value="ADD_COMPONENT">ADD COMPONENT</option>
            <option value="MULTIPLY_COMPONENT">MULTIPLY COMPONENT</option>
            <option value="SUBTRACT_COMPONENT">SUBTRACT COMPONENT</option>
        </select>
        @if (!empty($isEditMode))
            <div class="existing-value mb-2">
                <b>Data saat ini:</b>
                <span id="existingActionType">{{ data_get($initialRule, 'definition.action.type', '-') }}</span>
            </div>
        @endif

        <input id="actionValue" class="form-control" name="action_formula"
            placeholder="Contoh: attendance.late_minutes * rates.late_deduction_per_minute" />
        @if (!empty($isEditMode))
            <div class="existing-value">
                <b>Data saat ini:</b>
                <span id="existingActionFormula">{{ data_get($initialRule, 'definition.action.formula', '-') }}</span>
            </div>
        @endif

        <div class="muted mt-2">
            Gunakan variable seperti employee.*, attendance.*, components.*, rates.*
        </div>
    </div>

    {{-- HIDDEN INPUTS --}}
    <input type="hidden" id="definitionJson" name="definition_json">
</div>

{{-- ===================== TEMPLATES ===================== --}}
<template id="conditionRowTemplate">
    <div class="condition-row builder-item">
        <div class="condition-grid">
            <select class="field custom-select">
                <option value="employee.status">employee.status</option>
                <option value="employee.contract_type">employee.contract_type</option>
                <option value="employee.grade">employee.grade</option>
                <option value="employee.join_date">employee.join_date</option>
                <option value="employee.basic_salary">employee.basic_salary</option>

                <option value="attendance.days_present">attendance.days_present</option>
                <option value="attendance.days_absent">attendance.days_absent</option>
                <option value="attendance.late_minutes">attendance.late_minutes</option>
                <option value="attendance.overtime_hours">attendance.overtime_hours</option>

                <option value="components.BASIC_SALARY">components.BASIC_SALARY</option>
                <option value="components.TH_R">components.TH_R</option>
                <option value="components.OVERTIME_PAY">components.OVERTIME_PAY</option>

                <option value="rates.late_deduction_per_minute">rates.late_deduction_per_minute</option>
                <option value="rates.overtime_per_hour">rates.overtime_per_hour</option>
            </select>

            <select class="operator custom-select">
                <option value="==">==</option>
                <option value="!=">!=</option>
                <option value=">">&gt;</option>
                <option value="<">&lt;</option>
                <option value=">=">&gt;=</option>
                <option value="<=">&lt;=</option>
                <option value="IN">IN</option>
                <option value="NOT_IN">NOT IN</option>
                <option value="CONTAINS">CONTAINS</option>
            </select>

            <input type="text" class="value form-control" placeholder='Contoh: Tetap atau 10'>
        </div>

        <div class="builder-actions-inline mt-2">
            <button type="button" class="btn-mini btn-danger-soft remove-condition">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
    </div>
</template>

<template id="conditionGroupTemplate">
    <div class="condition-group builder-item">
        <div class="group-header">
            <div class="group-header-left">
                <span class="group-badge">GROUP</span>
                <select class="group-type custom-select">
                    <option value="AND">AND</option>
                    <option value="OR">OR</option>
                </select>
            </div>

            <div class="group-header-right">
                <button type="button" class="btn-mini add-condition-inside">
                    <i class="fas fa-plus"></i> Kondisi
                </button>

                <button type="button" class="btn-mini add-group-inside">
                    <i class="fas fa-project-diagram"></i> Group
                </button>

                <button type="button" class="btn-mini btn-danger-soft remove-group">
                    <i class="fas fa-trash"></i> Hapus Group
                </button>
            </div>
        </div>

        <div class="group-children"></div>
    </div>
</template>

<style>
    .rule-center-panel .panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .block-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #eef2ff;
        color: #3730a3;
    }

    .title {
        font-weight: 700;
        color: #111827;
    }

    .badge-wajib {
        margin-left: auto;
        background: #fee2e2;
        color: #991b1b;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .muted {
        font-size: 13px;
        color: #6b7280;
    }

    .custom-select,
    .form-control {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fff;
        outline: none;
    }

    .custom-select:focus,
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
    }

    .builder-toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-builder,
    .btn-mini {
        border: 0;
        border-radius: 10px;
        padding: 9px 12px;
        cursor: pointer;
        font-weight: 600;
        background: #4f46e5;
        color: #fff;
    }

    .btn-builder-secondary {
        background: #0f766e;
    }

    .btn-mini {
        padding: 7px 10px;
        font-size: 12px;
        background: #e5e7eb;
        color: #111827;
    }

    .btn-danger-soft {
        background: #fee2e2;
        color: #991b1b;
    }

    .condition-root {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .builder-item {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fafafa;
    }

    .condition-row {
        padding: 14px;
    }

    .condition-grid {
        display: grid;
        grid-template-columns: 1.3fr .8fr 1fr;
        gap: 10px;
    }

    .builder-actions-inline {
        display: flex;
        justify-content: flex-end;
    }

    .condition-group {
        padding: 14px;
        border-left: 4px solid #4f46e5;
        background: #f8fafc;
    }

    .group-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .group-header-left,
    .group-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .group-badge {
        font-size: 12px;
        font-weight: 700;
        color: #4338ca;
        background: #e0e7ff;
        padding: 4px 10px;
        border-radius: 999px;
    }

    .group-children {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-left: 10px;
    }

    .json-preview {
        margin: 0;
        min-height: 180px;
        max-height: 420px;
        overflow: auto;
        background: #0f172a;
        color: #e2e8f0;
        padding: 14px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .condition-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const conditionsRoot = document.getElementById('conditionsRoot');
        const jsonPreview = document.getElementById('jsonPreview');
        const definitionInput = document.getElementById('definitionJson');

        const ruleNameInput = document.getElementById('ruleName');
        const actionCodeInput = document.getElementById('actionCode');
        const actionTypeInput = document.getElementById('actionType');
        const actionValueInput = document.getElementById('actionValue');

        const addRootConditionBtn = document.getElementById('addRootConditionBtn');
        const addRootGroupBtn = document.getElementById('addRootGroupBtn');

        function createConditionRow() {
            const template = document.getElementById('conditionRowTemplate');
            return template.content.firstElementChild.cloneNode(true);
        }

        function createGroup() {
            const template = document.getElementById('conditionGroupTemplate');
            return template.content.firstElementChild.cloneNode(true);
        }

        function appendCondition(targetContainer) {
            const row = createConditionRow();
            targetContainer.appendChild(row);
            bindRowEvents(row);
            updatePreview();
        }

        function appendGroup(targetContainer) {
            const group = createGroup();
            targetContainer.appendChild(group);
            bindGroupEvents(group);
            updatePreview();
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
            const value = Array.isArray(cond.value) ?
                cond.value.join(", ") :
                cond.value;

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

        function buildRuleSentence() {
            const payload = buildRulePayload();
            const cond = payload.definition.conditions;
            const action = payload.definition.action;
            const conditionSentence = rulesToSentence(cond);
            const actionSentence =
                `${action.type.replaceAll("_"," ").toLowerCase()} komponen ${action.code} dengan formula ${action.formula}`;

            return `Jika ${conditionSentence} maka ${actionSentence}`;
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
                    action: {
                        type: actionTypeInput.value,
                        code: actionCodeInput.value,
                        formula: actionValueInput.value.trim()
                    }
                }
            };
        }

        function updatePreview() {
            const payload = buildRulePayload();
            const ruleSentence = buildRuleSentence();
            document.getElementById("ruleSentencePreview").innerText = ruleSentence;
            definitionInput.value = JSON.stringify(payload.definition);
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

        appendCondition(conditionsRoot);
        updatePreview();
    });
</script> --}}
