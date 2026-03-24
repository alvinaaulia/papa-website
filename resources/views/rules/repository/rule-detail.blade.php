@extends('layouts.app-hrd')

@section('title', 'Detail Aturan')

@php
    $definition = is_array($ruleVersion->definition) ? $ruleVersion->definition : [];
    $conditions = $definition['conditions'] ?? [];
    $action = $definition['action'] ?? [];

    $operatorText = [
        '==' => 'sama dengan',
        '!=' => 'tidak sama dengan',
        '>' => 'lebih besar dari',
        '<' => 'lebih kecil dari',
        '>=' => 'lebih besar atau sama dengan',
        '<=' => 'lebih kecil atau sama dengan',
        'IN' => 'termasuk',
        'NOT_IN' => 'tidak termasuk',
        'CONTAINS' => 'mengandung',
    ];

    $actionTypeText = [
        'SET_COMPONENT' => 'Set komponen',
        'ADD_COMPONENT' => 'Tambah komponen',
        'MULTIPLY_COMPONENT' => 'Kalikan komponen',
        'SUBTRACT_COMPONENT' => 'Kurangi komponen',
    ];

    $formatValue = function ($value) {
        if (is_array($value)) {
            return implode(
                ', ',
                array_map(fn($item) => is_scalar($item) ? (string) $item : json_encode($item), $value),
            );
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        if ($value === null || $value === '') {
            return '-';
        }

        return (string) $value;
    };

    $conditionToText = function ($node) use (&$conditionToText, $operatorText, $formatValue) {
        if (!is_array($node) || empty($node)) {
            return '-';
        }

        if (isset($node['rules']) && is_array($node['rules'])) {
            $connector = strtoupper($node['type'] ?? 'AND');
            $glue = $connector === 'OR' ? ' ATAU ' : ' DAN ';

            $parts = collect($node['rules'])
                ->map(fn($child) => $conditionToText($child))
                ->filter(fn($text) => filled($text) && $text !== '-')
                ->values();

            if ($parts->isEmpty()) {
                return '-';
            }

            if ($parts->count() === 1) {
                return $parts->first();
            }

            return '(' . $parts->implode($glue) . ')';
        }

        $field = $node['field'] ?? '-';
        $operator = $operatorText[$node['operator'] ?? ''] ?? ($node['operator'] ?? '-');
        $value = $formatValue($node['value'] ?? null);

        return "{$field} {$operator} {$value}";
    };

    if (is_array($conditions) && array_is_list($conditions)) {
        $conditions = [
            'type' => 'AND',
            'rules' => $conditions,
        ];
    }

    $statusBadgeClass = match (strtoupper((string) $ruleVersion->status)) {
        'ACTIVE' => 'badge-aktif',
        'DRAFT' => 'badge-draft',
        default => 'badge-nonaktif',
    };

    $approvalBadgeClass = match (strtoupper((string) $ruleVersion->approval_status)) {
        '', 'DRAFT' => 'badge-nonaktif',
        'APPROVED' => 'badge-approved',
        'REJECTED' => 'badge-rejected',
        default => 'badge-pending',
    };

    $approvalLabel = match (strtoupper((string) $ruleVersion->approval_status)) {
        '', 'DRAFT' => 'Belum diajukan',
        'APPROVED' => 'Approved',
        'REJECTED' => 'Rejected',
        default => 'Pending',
    };

    $actionType = $actionTypeText[$action['type'] ?? ''] ?? ($action['type'] ?? 'Aksi');
    $actionCode = $action['code'] ?? '-';
    $formula = $action['formula'] ?? '-';
@endphp

@push('style')
    <style>
        .detail-card {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .detail-title {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .detail-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-aktif {
            background: #e8f7ec;
            color: #166534;
        }

        .badge-nonaktif {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-draft {
            background: #fff7ed;
            color: #9a3412;
        }

        .badge-approved {
            background: #dcfce7;
            color: #166534;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .meta-item {
            padding: 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .meta-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .meta-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .section-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        .summary-text {
            line-height: 1.8;
            color: #374151;
        }

        .component-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .component-chip {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            font-size: 13px;
        }

        .json-block {
            margin: 0;
            padding: 16px;
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 12px;
            overflow: auto;
            font-size: 13px;
        }

        .version-list {
            display: grid;
            gap: 12px;
        }

        .version-item {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            background: #fff;
        }

        .version-item.active-version {
            border-color: #93c5fd;
            background: #eff6ff;
        }

        .version-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .version-name {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .version-sub {
            font-size: 13px;
            color: #6b7280;
        }

        .alert-success-soft {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div>
                    <h1 class="mb-0">Detail Aturan</h1>
                </div>
            </div>

            <div class="card detail-card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert-success-soft">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="detail-header">
                        <div>
                            <div class="detail-title">{{ $ruleVersion->rule?->name ?? '-' }}</div>
                            <div class="detail-subtitle">
                                Rule version ID #{{ $ruleVersion->id }} • Dibuat
                                {{ $ruleVersion->created_at?->format('d M Y H:i') ?? '-' }}
                            </div>
                        </div>

                        <div class="d-flex flex-column align-items-start align-items-md-end" style="gap:10px;">
                            <span class="badge-status {{ $statusBadgeClass }}">
                                <i class="fas fa-circle" style="font-size:8px;"></i>
                                {{ ucfirst(strtolower((string) $ruleVersion->status)) }}
                            </span>

                            <span class="badge-status {{ $approvalBadgeClass }}">
                                <i class="fas fa-check-circle" style="font-size:10px;"></i>
                                {{ $approvalLabel }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap:12px;">
                        <div class="text-muted">
                            Buat draft versi baru dari aturan ini, edit isinya, lalu ajukan approval setelah perubahan
                            selesai.
                        </div>

                        <form method="POST" action="{{ route('hrd.rules.versions.store', $ruleVersion) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-code-branch mr-1"></i> Buat Draft Versi Baru
                            </button>
                        </form>
                    </div>

                    @if ($ruleVersion->status === 'DRAFT')
                        <div class="section-card">
                            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                <div>
                                    <div class="section-title mb-1">Aksi Draft</div>
                                    <div class="text-muted">Draft ini masih bisa Anda edit sebelum diajukan.</div>
                                </div>

                                <a href="{{ route('hrd.rules.edit', $ruleVersion) }}" class="btn btn-primary">
                                    <i class="fas fa-pen mr-1"></i> Edit Draft
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="meta-label">Versi</div>
                            <div class="meta-value">{{ $ruleVersion->version ?? '-' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Kode Komponen</div>
                            <div class="meta-value">{{ $actionCode }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Jenis Aksi</div>
                            <div class="meta-value">{{ $actionType }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Aktivasi</div>
                            <div class="meta-value">{{ $ruleVersion->activated_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-title">Riwayat Versi</div>
                        <div class="version-list">
                            @foreach ($versions as $versionItem)
                                @php
                                    $versionStatusClass = match (strtoupper((string) $versionItem->status)) {
                                        'ACTIVE' => 'badge-aktif',
                                        'DRAFT' => 'badge-draft',
                                        default => 'badge-nonaktif',
                                    };

                                    $versionApprovalClass = match (strtoupper((string) $versionItem->approval_status)) {
                                        '', 'DRAFT' => 'badge-nonaktif',
                                        'APPROVED' => 'badge-approved',
                                        'REJECTED' => 'badge-rejected',
                                        default => 'badge-pending',
                                    };

                                    $versionApprovalLabel = match (strtoupper((string) $versionItem->approval_status)) {
                                        '', 'DRAFT' => 'Belum diajukan',
                                        'APPROVED' => 'Approved',
                                        'REJECTED' => 'Rejected',
                                        default => 'Pending',
                                    };
                                @endphp

                                <div class="version-item {{ $versionItem->is($ruleVersion) ? 'active-version' : '' }}">
                                    <div class="version-top">
                                        <div>
                                            <div class="version-name">Versi {{ $versionItem->version }}</div>
                                            <div class="version-sub">
                                                ID #{{ $versionItem->id }} •
                                                {{ $versionItem->created_at?->format('d M Y H:i') ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap" style="gap:8px;">
                                            <span class="badge-status {{ $versionStatusClass }}">
                                                {{ ucfirst(strtolower((string) $versionItem->status)) }}
                                            </span>
                                            <span class="badge-status {{ $versionApprovalClass }}">
                                                {{ $versionApprovalLabel }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                                        <div class="version-sub">
                                            Komponen:
                                            {{ $versionItem->salaryComponents->pluck('component_code')->filter()->join(', ') ?: '-' }}
                                        </div>

                                        @if ($versionItem->is($ruleVersion))
                                            <span class="badge-status badge-approved">Versi yang sedang dibuka</span>
                                        @elseif ($versionItem->status === 'DRAFT')
                                            <a href="{{ route('hrd.rules.edit', $versionItem) }}" class="btn btn-primary btn-sm">
                                                Lanjut Edit
                                            </a>
                                        @else
                                            <a href="{{ route('hrd.rules.show', $versionItem) }}" class="btn btn-light btn-sm">
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-title">Ringkasan If-Then</div>
                        <div class="summary-text">
                            <b>IF</b> {{ $conditionToText($conditions) }}<br>
                            <b>THEN</b> {{ $actionType }} <b>{{ $actionCode }}</b> dengan formula
                            <code>{{ $formula }}</code>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-title">Komponen Terkait</div>
                        @if ($ruleVersion->salaryComponents->isNotEmpty())
                            <div class="component-list">
                                @foreach ($ruleVersion->salaryComponents as $component)
                                    <span class="component-chip">
                                        {{ $component->component_code ?? $component->component_name ?? ('ID ' . ($component->component_id ?? '-')) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">Belum ada komponen terkait.</div>
                        @endif
                    </div>

                    <div class="section-card">
                        <div class="section-title">Definition JSON</div>
                        <pre class="json-block">{{ json_encode($definition, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                        <a href="{{ route('hrd.rules') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                        </a>

                        <div class="text-muted small">
                            Approval notes: {{ $ruleVersion->decision_notes ?: '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
