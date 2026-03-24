@extends('layouts.app-director')

@section('title', 'Detail Persetujuan Aturan')

@php
    $definition = is_array($ruleVersion->definition) ? $ruleVersion->definition : [];
    $action = $definition['action'] ?? [];
    $conditions = $definition['conditions'] ?? [];

    $formatValue = function ($value) {
        if (is_array($value)) {
            return implode(', ', array_map(fn($item) => is_scalar($item) ? (string) $item : json_encode($item), $value));
        }
        if ($value === null || $value === '') {
            return '-';
        }
        return (string) $value;
    };

    $conditionToText = function ($node) use (&$conditionToText, $formatValue) {
        if (!is_array($node) || empty($node)) {
            return '-';
        }

        if (isset($node['rules']) && is_array($node['rules'])) {
            $connector = strtoupper($node['type'] ?? 'AND');
            $glue = $connector === 'OR' ? ' ATAU ' : ' DAN ';

            return collect($node['rules'])
                ->map(fn($child) => $conditionToText($child))
                ->filter()
                ->implode($glue);
        }

        return ($node['field'] ?? '-') . ' ' . ($node['operator'] ?? '-') . ' ' . $formatValue($node['value'] ?? null);
    };

    if (is_array($conditions) && array_is_list($conditions)) {
        $conditions = ['type' => 'AND', 'rules' => $conditions];
    }
@endphp

@push('style')
    <style>
        .detail-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
            overflow: hidden;
        }

        .detail-header {
            padding: 22px;
            border-bottom: 1px solid #eef2f7;
        }

        .detail-title {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .detail-sub {
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
        }

        .section-box {
            padding: 20px 22px;
            border-bottom: 1px solid #eef2f7;
        }

        .section-box:last-child {
            border-bottom: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .summary-text {
            line-height: 1.8;
            color: #334155;
        }

        .json-box {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .json-box pre {
            margin: 0;
            padding: 14px;
            font-size: 12px;
            background: #f8fafc;
            overflow: auto;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                <div>
                    <h1 class="mb-0">Detail Persetujuan Aturan</h1>
                    <div class="text-muted">Tinjau isi aturan sebelum mengambil keputusan.</div>
                </div>

                <a href="{{ route('rule-approval-director') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-title">{{ $ruleVersion->rule?->name ?? '-' }}</div>
                    <div class="detail-sub">
                        Versi {{ $ruleVersion->version }} • Diajukan {{ $ruleVersion->created_at?->format('d M Y H:i') ?? '-' }}<br>
                        Status approval: {{ $ruleVersion->approval_status ?? '-' }}
                    </div>
                </div>

                <div class="section-box">
                    <div class="section-title">Ringkasan Rule</div>
                    <div class="summary-text">
                        <b>IF</b> {{ $conditionToText($conditions) }}<br>
                        <b>THEN</b> {{ $action['type'] ?? '-' }} <b>{{ $action['code'] ?? '-' }}</b> dengan formula
                        <code>{{ $action['formula'] ?? '-' }}</code>
                    </div>
                </div>

                <div class="section-box">
                    <div class="section-title">Definition JSON</div>
                    <div class="json-box">
                        <pre>{{ json_encode($definition, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>

                <div class="section-box">
                    <div class="section-title">Keputusan Direktur</div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <form method="POST" action="{{ route('rule-approval-approve-director', $ruleVersion) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Catatan Approval</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="Opsional, misalnya alasan approval atau arahan implementasi."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Approve Rule
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6 mb-3">
                            <form method="POST" action="{{ route('rule-approval-reject-director', $ruleVersion) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Catatan Revisi / Penolakan</label>
                                    <textarea name="notes" class="form-control" rows="4" placeholder="Wajib diisi untuk menjelaskan alasan reject." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Reject Rule
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
