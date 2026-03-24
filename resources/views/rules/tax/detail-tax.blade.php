@extends('layouts.app-hrd')

@section('title', 'Detail Pajak')

@push('style')
    <style>
        .info-card {
            border-radius: 14px;
            border: 1px solid #e3e6f0;
            padding: 25px;
            background: #fff;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-inactive {
            background-color: #e2e3e5;
            color: #6c757d;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .label-title {
            font-size: 13px;
            color: #6c757d;
        }

        .value-text {
            font-weight: 600;
            font-size: 15px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Detail Pajak</h1>
                    <div class="page-subtitle" style="margin-top: 1px;">
                        Informasi lengkap master pajak {{ $tax->tax_code }}
                    </div>
                </div>

                <a href="{{ route('hrd.tax') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="info-card">
                            <div class="mb-4">
                                <h4 class="mb-1">{{ $tax->tax_name }}</h4>
                                <small class="text-muted">Kode: {{ $tax->tax_code }}</small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="label-title">Tipe Pajak</div>
                                    <div class="value-text mt-1">{{ $tax->tax_type }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="label-title">Metode</div>
                                    <div class="value-text mt-1">{{ $tax->calculation_method }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="label-title">Status</div>
                                    <div class="mt-1">
                                        <span class="{{ $tax->is_active ? 'badge-active' : 'badge-inactive' }}">
                                            {{ $tax->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="label-title">Tarif (%)</div>
                                    <div class="value-text mt-1">{{ $tax->tax_rate ?? '-' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="label-title">Income Minimum</div>
                                    <div class="value-text mt-1">{{ $tax->income_min ?? '-' }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="label-title">Income Maksimum</div>
                                    <div class="value-text mt-1">{{ $tax->income_max ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="label-title">Tanggal Berlaku</div>
                                    <div class="value-text mt-1">{{ $tax->effective_date?->format('d M Y') ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label-title">Tanggal Berakhir</div>
                                    <div class="value-text mt-1">{{ $tax->end_date?->format('d M Y') ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="label-title">Deskripsi</div>
                                <div class="value-text mt-1">{{ $tax->description ?? '-' }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="label-title">Dibuat Pada</div>
                                    <div class="value-text mt-1">{{ $tax->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label-title">Diperbarui Pada</div>
                                    <div class="value-text mt-1">{{ $tax->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
