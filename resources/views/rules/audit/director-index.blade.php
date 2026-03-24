@extends('layouts.app-director')

@section('title', 'Audit Trail Direktur')

@php
    $actionLabel = function ($action) {
        return match ((string) $action) {
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
            default => str_replace('_', ' ', (string) $action),
        };
    };

    $actionBadge = function ($action) {
        return match (true) {
            str_starts_with((string) $action, 'RULE') => 'badge-rule',
            str_starts_with((string) $action, 'COMPONENT') => 'badge-component',
            default => 'badge-default',
        };
    };
@endphp

@push('style')
    <style>
        .audit-hero {
            border-radius: 18px;
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 24%),
                linear-gradient(135deg, #111827, #1f2937 46%, #0f766e);
            color: #fff;
            margin-bottom: 20px;
        }

        .audit-hero h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .audit-hero p {
            margin: 0;
            max-width: 760px;
            color: rgba(255,255,255,.84);
            line-height: 1.7;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card,
        .audit-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .summary-card {
            padding: 18px;
        }

        .summary-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
        }

        .summary-note {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .audit-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eef2f7;
        }

        .audit-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .audit-card-subtitle {
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
        }

        .audit-card-body {
            padding: 20px;
        }

        .timeline {
            display: grid;
            gap: 16px;
        }

        .timeline-item {
            padding: 18px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .timeline-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .timeline-title {
            font-weight: 800;
            color: #0f172a;
        }

        .timeline-meta {
            color: #64748b;
            font-size: 13px;
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

        @media (max-width: 992px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="audit-hero">
                <h1>Audit Trail Direktur</h1>
                <p>
                    Direktur dapat meninjau seluruh kronologis aktivitas rule penggajian dan komponen gaji sebagai dasar
                    evaluasi, pengawasan, dan pengambilan keputusan approval.
                </p>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total Aktivitas</div>
                    <div class="summary-value">{{ $summary['total_logs'] }}</div>
                    <div class="summary-note">Seluruh log audit yang tersimpan</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Hari Ini</div>
                    <div class="summary-value">{{ $summary['today_logs'] }}</div>
                    <div class="summary-note">Aktivitas terbaru yang perlu ditinjau</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Aktivitas Rule</div>
                    <div class="summary-value">{{ $summary['rule_logs'] }}</div>
                    <div class="summary-note">Log terkait pembuatan, update, approval, dan aktivasi rule</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Aktivitas Komponen</div>
                    <div class="summary-value">{{ $summary['component_logs'] }}</div>
                    <div class="summary-note">Log terkait komponen gaji dan dampaknya</div>
                </div>
            </div>

            <div class="audit-card">
                <div class="audit-card-header">
                    <h3 class="audit-card-title">Kronologis Aktivitas</h3>
                    <div class="audit-card-subtitle">
                        Menampilkan histori sistem secara kronologis. Buka detail untuk melihat kondisi sebelum dan sesudah perubahan.
                    </div>
                </div>

                <div class="audit-card-body">
                    <div class="timeline">
                        @forelse ($logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-head">
                                    <div>
                                        <div class="timeline-title">{{ $actionLabel($log->action_type) }}</div>
                                        <div class="timeline-meta">
                                            Oleh {{ $log->user?->name ?? 'System / Unknown User' }}
                                            @if ($log->ruleVersion?->rule?->name)
                                                • Rule: {{ $log->ruleVersion->rule->name }}
                                            @endif
                                            @if ($log->ruleVersion?->version)
                                                • Versi {{ $log->ruleVersion->version }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="audit-badge {{ $actionBadge($log->action_type) }}">
                                            {{ $log->action_type }}
                                        </span>
                                        <div class="timeline-meta mt-2">
                                            {{ $log->action_date?->format('d M Y H:i:s') ?? '-' }}<br>
                                            IP {{ $log->ip_address ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="timeline-meta">
                                    {{ $log->notes ?: 'Tidak ada catatan tambahan.' }}
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('audit-trail-detail-director', $log) }}" class="btn btn-light btn-sm">
                                        Lihat Detail Perubahan
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                Belum ada aktivitas yang tercatat di audit trail.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
