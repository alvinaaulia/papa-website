@extends('layouts.app-hrd')

@section('title', 'Pengelolaan Pajak')

@php
    $taxes = $taxes ?? new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
    $activeCount = $taxes->getCollection()->where('is_active', true)->count();
    $inactiveCount = $taxes->getCollection()->where('is_active', false)->count();
@endphp

@push('style')
    <style>
        .tax-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card,
        .tax-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .04);
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
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .summary-note {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .tax-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: .06em;
            border-top: 0;
            white-space: nowrap;
        }

        .status-badge {
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

        .status-inactive {
            background: #f1f5f9;
            color: #475569;
        }

        .tax-name {
            font-weight: 700;
            color: #0f172a;
        }

        .tax-code {
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 992px) {
            .tax-summary {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .tax-summary {
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
                    <h1 class="mb-0">Pengelolaan Pajak</h1>
                    <div class="text-muted">Kelola master pajak perusahaan untuk payroll dan kepatuhan.</div>
                </div>

                <a href="{{ route('hrd.tax.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Pajak
                </a>
            </div>

            <div class="tax-summary">
                <div class="summary-card">
                    <div class="summary-label">Total Master</div>
                    <div class="summary-value">{{ method_exists($taxes, 'total') ? $taxes->total() : $taxes->count() }}</div>
                    <div class="summary-note">Data pajak perusahaan yang terdaftar</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Aktif</div>
                    <div class="summary-value">{{ $activeCount }}</div>
                    <div class="summary-note">Siap dipakai dalam proses payroll</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Nonaktif</div>
                    <div class="summary-value">{{ $inactiveCount }}</div>
                    <div class="summary-note">Tidak dipakai dalam proses berjalan</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Metode</div>
                    <div class="summary-value">{{ $taxes->getCollection()->pluck('calculation_method')->unique()->count() }}</div>
                    <div class="summary-note">Jenis metode perhitungan yang digunakan</div>
                </div>
            </div>

            <div class="card tax-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover tax-table mb-0">
                            <thead>
                                <tr>
                                    <th>Pajak</th>
                                    <th>Tipe</th>
                                    <th>Metode</th>
                                    <th>Tarif</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($taxes as $tax)
                                    <tr>
                                        <td>
                                            <div class="tax-name">{{ $tax->tax_name }}</div>
                                            <div class="tax-code">{{ $tax->tax_code }}</div>
                                        </td>
                                        <td>{{ $tax->tax_type }}</td>
                                        <td>{{ $tax->calculation_method }}</td>
                                        <td>{{ $tax->tax_rate !== null ? rtrim(rtrim(number_format((float) $tax->tax_rate, 4, '.', ''), '0'), '.') . '%' : '-' }}</td>
                                        <td>
                                            {{ $tax->effective_date?->format('d M Y') ?? '-' }}
                                            @if ($tax->end_date)
                                                <br><span class="text-muted">s/d {{ $tax->end_date->format('d M Y') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $tax->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $tax->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('hrd.tax.detail', $tax) }}" class="text-primary font-weight-600">
                                                Detail <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            Belum ada master pajak. Silakan tambahkan data pajak perusahaan pertama.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap" style="gap:12px;">
                        <div class="text-muted small">
                            @if (method_exists($taxes, 'total'))
                                Menampilkan {{ $taxes->firstItem() ?? 0 }} sampai {{ $taxes->lastItem() ?? 0 }} dari {{ $taxes->total() }} data pajak
                            @else
                                Menampilkan {{ $taxes->count() }} data pajak
                            @endif
                        </div>

                        <div>
                            @if (method_exists($taxes, 'links'))
                                {{ $taxes->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
