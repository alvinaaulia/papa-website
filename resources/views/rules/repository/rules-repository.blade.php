@extends('layouts.app-hrd')

@section('title', 'Aturan Gaji')

@php
    $rules = $rules ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

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

    $statusBadgeClass = function ($status) {
        return match (strtoupper((string) $status)) {
            'ACTIVE' => 'badge-aktif',
            'DRAFT' => 'badge-draft',
            default => 'badge-nonaktif',
        };
    };

    $approvalBadgeClass = function ($status) {
        return match (strtoupper((string) $status)) {
            'APPROVED' => 'badge-approved',
            'REJECTED' => 'badge-rejected',
            default => 'badge-pending',
        };
    };
@endphp

@push('style')
    <style>
        .rule-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        }

        .rule-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: .5px;
            border-top: 0;
            white-space: nowrap;
        }

        .rule-table td {
            vertical-align: middle;
        }

        .rule-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            flex-shrink: 0;
        }

        .rule-name {
            font-weight: 700;
            color: #111827;
        }

        .rule-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        .rule-summary {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
            min-width: 320px;
        }

        .rule-summary b {
            color: #111827;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
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

        .status-stack {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 28px;
            margin-bottom: 10px;
            color: #cbd5e1;
        }

        .btn-detail {
            font-weight: 600;
            text-decoration: none;
        }

        .footer-meta {
            font-size: 13px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .rule-summary {
                min-width: 240px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">Daftar Aturan</h1>
                </div>

                <a href="{{ url('/rules/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Aturan Baru
                </a>
            </div>

            <div class="card rule-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover rule-table">
                            <thead>
                                <tr>
                                    <th>Nama Aturan</th>
                                    <th>Ringkasan If-Then</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rules as $rule)
                                    @php
                                        $version = $rule->latestVersion;

                                        $definition = is_array($version?->definition) ? $version->definition : [];
                                        $conditions = $definition['conditions'] ?? [];
                                        $action = $definition['action'] ?? [];

                                        $conditionText = $conditionToText($conditions);

                                        $actionType =
                                            $actionTypeText[$action['type'] ?? ''] ?? ($action['type'] ?? 'Aksi');
                                        $actionCode = $action['code'] ?? '-';
                                        $formula = $action['formula'] ?? '-';

                                        $status = strtoupper((string) ($version?->status ?? 'DRAFT'));
                                        $approvalStatus = strtoupper(
                                            (string) ($version?->approval_status ?? 'PENDING'),
                                        );
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rule-icon mr-3">
                                                    <i class="fas fa-bolt"></i>
                                                </div>

                                                <div>
                                                    <div class="rule-name">{{ $rule->name }}</div>
                                                    <div class="rule-sub">
                                                        Dibuat {{ $rule->created_at?->diffForHumans() ?? '-' }}
                                                        @if ($version?->version)
                                                            • Versi {{ $version->version }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="rule-summary">
                                            <b>IF</b> {{ $conditionText }}<br>
                                            <b>THEN</b> {{ $actionType }} <b>{{ $actionCode }}</b> dengan formula
                                            <code>{{ $formula }}</code>
                                        </td>

                                        <td>
                                            <div class="status-stack">
                                                <span class="badge-status {{ $statusBadgeClass($status) }}">
                                                    <i class="fas fa-circle" style="font-size:8px;"></i>
                                                    {{ ucfirst(strtolower($status)) }}
                                                </span>

                                                <span class="badge-status {{ $approvalBadgeClass($approvalStatus) }}">
                                                    <i class="fas fa-check-circle" style="font-size:10px;"></i>
                                                    {{ ucfirst(strtolower($approvalStatus)) }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" class="text-primary btn-detail">
                                                Detail <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <div><i class="fas fa-inbox"></i></div>
                                                <div class="font-weight-600 mb-1">Belum ada aturan</div>
                                                <div>Silakan buat aturan pertama untuk penggajian.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="footer-meta">
                            @if (method_exists($rules, 'total'))
                                Menampilkan {{ $rules->firstItem() ?? 0 }} sampai {{ $rules->lastItem() ?? 0 }}
                                dari {{ $rules->total() }} aturan
                            @else
                                Menampilkan {{ $rules->count() }} aturan
                            @endif
                        </div>

                        <div>
                            @if (method_exists($rules, 'links'))
                                {{ $rules->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
