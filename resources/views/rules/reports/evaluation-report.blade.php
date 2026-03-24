@extends('layouts.app-hrd')

@section('title', 'Laporan Evaluasi Aturan Penggajian')

@php
    $statusBadgeClass = function ($status) {
        return match (strtoupper((string) $status)) {
            'ACTIVE' => 'status-active',
            'DRAFT' => 'status-draft',
            default => 'status-inactive',
        };
    };

    $approvalBadgeClass = function ($status) {
        return match (strtoupper((string) $status)) {
            'APPROVED' => 'status-approved',
            'REJECTED' => 'status-rejected',
            default => 'status-pending',
        };
    };
@endphp

@push('style')
    <style>
        .report-hero {
            border-radius: 18px;
            padding: 24px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, .18), transparent 24%),
                linear-gradient(135deg, #0f172a, #1d4ed8 55%, #38bdf8);
            margin-bottom: 20px;
        }

        .report-hero h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .report-hero p {
            margin-bottom: 0;
            max-width: 760px;
            color: rgba(255, 255, 255, .86);
            line-height: 1.7;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card,
        .report-card {
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

        .report-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eef2f7;
        }

        .report-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .report-card-subtitle {
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
        }

        .report-card-body {
            padding: 20px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .status-inactive {
            background: #e2e8f0;
            color: #475569;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #64748b;
            border-top: 0;
            white-space: nowrap;
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
            <div class="report-hero">
                <h1>Laporan Evaluasi Aturan Penggajian</h1>
                <p>
                    Pantau kesehatan repository aturan penggajian berdasarkan jumlah rule, distribusi versi, approval,
                    dan status versi terbaru yang sedang dipakai atau masih perlu tindak lanjut.
                </p>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total Aturan</div>
                    <div class="summary-value">{{ $summary['total_rules'] }}</div>
                    <div class="summary-note">Master aturan yang sudah memiliki minimal satu versi</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Versi</div>
                    <div class="summary-value">{{ $summary['total_versions'] }}</div>
                    <div class="summary-note">Seluruh versi aturan yang tercatat di repository</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Versi Aktif</div>
                    <div class="summary-value">{{ $summary['active_versions'] }}</div>
                    <div class="summary-note">Versi yang saat ini aktif dipakai dalam evaluasi payroll</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Draft</div>
                    <div class="summary-value">{{ $summary['draft_versions'] }}</div>
                    <div class="summary-note">Versi yang masih dalam proses penyusunan atau perubahan</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Pending Approval</div>
                    <div class="summary-value">{{ $summary['pending_approvals'] }}</div>
                    <div class="summary-note">Versi menunggu persetujuan direktur</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Rejected</div>
                    <div class="summary-value">{{ $summary['rejected_versions'] }}</div>
                    <div class="summary-note">Versi yang perlu revisi sebelum diajukan kembali</div>
                </div>
            </div>

            <div class="report-card">
                <div class="report-card-header">
                    <h3 class="report-card-title">Evaluasi Per Aturan</h3>
                    <div class="report-card-subtitle">
                        Menampilkan versi terbaru dari setiap rule beserta status approval dan total jumlah versinya.
                    </div>
                </div>

                <div class="report-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Aturan</th>
                                    <th>Versi Terbaru</th>
                                    <th>Status</th>
                                    <th>Approval</th>
                                    <th>Total Versi</th>
                                    <th>Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rules as $rule)
                                    @php
                                        $version = $rule->latestVersion;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-weight-700 text-dark">{{ $rule->name }}</div>
                                            <div class="text-muted small">
                                                Update terakhir {{ $version?->updated_at?->diffForHumans() ?? '-' }}
                                            </div>
                                        </td>
                                        <td>Versi {{ $version?->version ?? '-' }}</td>
                                        <td>
                                            <span class="status-pill {{ $statusBadgeClass($version?->status) }}">
                                                {{ $version?->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-pill {{ $approvalBadgeClass($version?->approval_status) }}">
                                                {{ $version?->approval_status ?: 'BELUM DIAJUKAN' }}
                                            </span>
                                        </td>
                                        <td>{{ $rule->versions_count }}</td>
                                        <td>
                                            @if (($version?->approval_status ?? null) === 'REJECTED')
                                                <span class="text-danger font-weight-600">Perlu revisi</span>
                                            @elseif (($version?->approval_status ?? null) === 'PENDING')
                                                <span class="text-warning font-weight-600">Menunggu approval</span>
                                            @elseif (($version?->status ?? null) === 'DRAFT')
                                                <span class="text-info font-weight-600">Selesaikan draft</span>
                                            @elseif (($version?->status ?? null) === 'ACTIVE')
                                                <span class="text-success font-weight-600">Sudah siap dipakai</span>
                                            @else
                                                <span class="text-muted">Perlu evaluasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            Belum ada data aturan penggajian untuk dievaluasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
