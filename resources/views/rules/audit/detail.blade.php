@extends('layouts.app-hrd')

@section('title', 'Detail Audit Trail')

@php
    $actionLabel = match ((string) $log->action_type) {
        'RULE_CREATE' => 'Buat Rule',
        'RULE_VERSION_CREATE' => 'Buat Versi Rule',
        'RULE_VERSION_SUBMIT' => 'Ajukan Approval',
        'RULE_VERSION_APPROVE' => 'Approve Rule',
        'RULE_VERSION_REJECT' => 'Reject Rule',
        'RULE_VERSION_ACTIVATE' => 'Aktifkan Versi',
        'RULE_VERSION_DISABLE' => 'Nonaktifkan Versi',
        'RULE_VERSION_UPDATE' => 'Update Draft Rule',
        'COMPONENT_CREATE' => 'Buat Komponen',
        'COMPONENT_UPDATE' => 'Update Komponen',
        'COMPONENT_DISABLE' => 'Nonaktifkan Komponen',
        'COMPONENT_ACTIVATE' => 'Aktifkan Komponen',
        'AUTO_RULE_INACTIVATE_BY_COMPONENT' => 'Auto Inactivate Rule',
        default => str_replace('_', ' ', (string) $log->action_type),
    };

    $actionBadge = match (true) {
        str_starts_with((string) $log->action_type, 'RULE') => 'badge-rule',
        str_starts_with((string) $log->action_type, 'COMPONENT') => 'badge-component',
        default => 'badge-default',
    };
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
            padding: 20px 22px;
            border-bottom: 1px solid #eef2f7;
        }

        .detail-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .detail-meta {
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
        }

        .audit-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-rule {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-component {
            background: #dcfce7;
            color: #166534;
        }

        .badge-default {
            background: #e2e8f0;
            color: #475569;
        }

        .detail-body {
            padding: 22px;
        }

        .note-box {
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            margin-bottom: 18px;
        }

        .compare-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .json-box {
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            background: #fff;
        }

        .json-title {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            font-weight: 700;
        }

        .json-box pre {
            margin: 0;
            padding: 14px;
            background: #fff;
            color: #0f172a;
            font-size: 12px;
            overflow: auto;
            max-height: 540px;
        }

        @media (max-width: 992px) {
            .compare-grid {
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
                    <h1 class="mb-0">Detail Audit Trail</h1>
                    <div class="text-muted">Bandingkan kondisi sebelum dan sesudah aktivitas dilakukan.</div>
                </div>

                <a href="{{ route('hrd.audit-trail') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
                        <div>
                            <div class="detail-title">{{ $actionLabel }}</div>
                            <div class="detail-meta">
                                Oleh {{ $log->user?->name ?? 'System / Unknown User' }}<br>
                                @if ($log->ruleVersion?->rule?->name)
                                    Rule: {{ $log->ruleVersion->rule->name }}
                                    @if ($log->ruleVersion?->version)
                                        • Versi {{ $log->ruleVersion->version }}
                                    @endif
                                    <br>
                                @endif
                                Waktu: {{ $log->action_date?->format('d M Y H:i:s') ?? '-' }}<br>
                                IP: {{ $log->ip_address ?? '-' }}
                            </div>
                        </div>

                        <span class="audit-badge {{ $actionBadge }}">
                            {{ $log->action_type }}
                        </span>
                    </div>
                </div>

                <div class="detail-body">
                    <div class="note-box">
                        {{ $log->notes ?: 'Tidak ada catatan tambahan pada aktivitas ini.' }}
                    </div>

                    <div class="compare-grid">
                        <div class="json-box">
                            <div class="json-title">Sebelum</div>
                            <pre>{{ $log->before_json ? json_encode($log->before_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 'Tidak ada data sebelum perubahan.' }}</pre>
                        </div>

                        <div class="json-box">
                            <div class="json-title">Sesudah</div>
                            <pre>{{ $log->after_json ? json_encode($log->after_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : 'Tidak ada data sesudah perubahan.' }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
