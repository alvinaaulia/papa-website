@extends('layouts.app-director')

@section('title', 'Persetujuan Direktur')

@push('style')
    <style>
        .approval-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .approval-hero {
            border-radius: 18px;
            padding: 24px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 24%),
                linear-gradient(135deg, #0f172a, #7c3aed 48%, #2563eb);
            margin-bottom: 20px;
        }

        .approval-hero h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .approval-hero p {
            margin: 0;
            max-width: 760px;
            color: rgba(255,255,255,.86);
            line-height: 1.7;
        }

        .summary-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            margin-top: 16px;
            font-weight: 700;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #64748b;
            border-top: 0;
            white-space: nowrap;
        }

        .section-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .section-subtitle {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .muted-note {
            color: #64748b;
            font-size: 12px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-draft {
            background: #e0f2fe;
            color: #075985;
        }

        .status-inactive {
            background: #e5e7eb;
            color: #374151;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="approval-hero">
                <h1>Persetujuan Direktur</h1>
                <p>
                    Halaman ini menampilkan semua versi aturan penggajian yang menunggu keputusan direktur.
                    Buka detail untuk meninjau rule, lalu approve atau reject sesuai hasil evaluasi.
                </p>
                <div class="summary-box">
                    <i class="fas fa-clock"></i> {{ $pendingVersions->total() }} pengajuan menunggu persetujuan
                </div>
            </div>

            <div class="approval-card mb-4">
                <div class="card-body">
                    <div class="section-title">Daftar Aturan</div>
                    <div class="section-subtitle">
                        Direktur dapat melihat seluruh aturan yang terdaftar, versi terbarunya, dan apakah ada pengajuan versi yang sedang menunggu persetujuan.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Aturan</th>
                                    <th>Versi Terbaru</th>
                                    <th>Status Versi</th>
                                    <th>Pending Approval</th>
                                    <th>Total Versi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rules as $rule)
                                    @php
                                        $latestVersion = $rule->latestVersion;
                                        $pendingVersion = $rule->versions->first();
                                        $versionStatusClass = match ($latestVersion?->status) {
                                            'ACTIVE' => 'status-approved',
                                            'DRAFT' => 'status-draft',
                                            'INACTIVE', 'ARCHIVED' => 'status-inactive',
                                            default => 'status-inactive',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-weight-700 text-dark">{{ $rule->name }}</div>
                                            <div class="muted-note">{{ $rule->description ?: 'Belum ada deskripsi aturan.' }}</div>
                                        </td>
                                        <td>{{ $latestVersion ? 'Versi ' . $latestVersion->version : '-' }}</td>
                                        <td>
                                            <span class="status-badge {{ $versionStatusClass }}">
                                                {{ $latestVersion?->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $rule->pending_versions_count > 0 ? 'status-pending' : 'status-inactive' }}">
                                                {{ $rule->pending_versions_count }} versi
                                            </span>
                                        </td>
                                        <td>{{ $rule->versions_count }}</td>
                                        <td>
                                            @if ($pendingVersion)
                                                <a href="{{ route('rule-approval-detail-director', $pendingVersion) }}" class="btn btn-primary btn-sm">
                                                    Tinjau Versi Pending
                                                </a>
                                            @elseif ($latestVersion)
                                                <a href="{{ route('rule-approval-detail-director', $latestVersion) }}" class="btn btn-light btn-sm">
                                                    Lihat Versi Terbaru
                                                </a>
                                            @else
                                                <span class="text-muted small">Tidak ada versi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            Belum ada aturan yang tersedia untuk ditinjau.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $rules->links() }}
                    </div>
                </div>
            </div>

            <div class="approval-card">
                <div class="card-body">
                    <div class="section-title">Daftar Pengajuan Versi</div>
                    <div class="section-subtitle">
                        Bagian ini fokus pada versi aturan yang saat ini benar-benar menunggu keputusan approval dari direktur.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Aturan</th>
                                    <th>Versi</th>
                                    <th>Diajukan</th>
                                    <th>Komponen Target</th>
                                    <th>Status Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingVersions as $version)
                                    @php
                                        $action = is_array($version->definition) ? ($version->definition['action'] ?? []) : [];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-weight-700 text-dark">{{ $version->rule?->name ?? '-' }}</div>
                                            <div class="text-muted small">Rule Version ID #{{ $version->id }}</div>
                                        </td>
                                        <td>Versi {{ $version->version }}</td>
                                        <td>{{ $version->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td>{{ $action['code'] ?? '-' }}</td>
                                        <td>
                                            <span class="status-badge status-pending">
                                                {{ $version->approval_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('rule-approval-detail-director', $version) }}" class="btn btn-primary btn-sm">
                                                Tinjau Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            Tidak ada rule yang sedang menunggu persetujuan direktur.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pendingVersions->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
