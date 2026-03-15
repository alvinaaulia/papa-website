@extends('layouts.app-hrd')

@section('title', 'Detail Komponen Gaji')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <style>
        .info-card {
            border-radius: 14px;
            border: 1px solid #e3e6f0;
            padding: 25px;
            background: #fff;
        }

        .badge-earning {
            background-color: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-deduction {
            background-color: #f8d7da;
            color: #721c24;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
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
                    <h1>Detail Komponen Gaji</h1>
                    <div class="page-subtitle" style="margin-top: 1px;">
                        Informasi lengkap komponen {{ $component->component_code }}
                    </div>
                </div>

                <a href="{{ route('hrd.components') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="section-body">

                <div class="row">

                    <!-- LEFT INFO -->
                    <div class="col-md-8">
                        <div class="info-card">

                            <div class="mb-4">
                                <h4 class="mb-1">{{ $component->component_name }}</h4>
                                <small class="text-muted">
                                    Kode: {{ $component->component_code }}
                                </small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="label-title">Kategori</div>
                                    <div class="mt-1">
                                        @if ($component->component_type === 'EARNING')
                                            <span class="badge-earning">+ Earning</span>
                                        @else
                                            <span class="badge-deduction">- Deduction</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="label-title">Status</div>
                                    <div class="mt-1">
                                        @if ($component->is_active)
                                            <span class="badge-active">● Aktif</span>
                                        @else
                                            <span class="badge-inactive">● Non-aktif</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="label-title">Kena Pajak</div>
                                    <div class="value-text mt-1">
                                        {{ $component->is_taxable ? 'Ya' : 'Tidak' }}
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="label-title">Deskripsi</div>
                                <div class="value-text mt-1">
                                    {{ $component->description ?? '-' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="label-title">Dibuat Pada</div>
                                    <div class="value-text mt-1">
                                        {{ $component->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="label-title">Terakhir Diupdate</div>
                                    <div class="value-text mt-1">
                                        {{ $component->updated_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- RIGHT ACTION -->
                    <div class="col-md-4">
                        <div class="info-card">

                            <h6 class="mb-3">Aksi</h6>

                            <a href="#" class="btn btn-warning btn-block mb-3">
                                <i class="fas fa-edit"></i> Edit Komponen
                            </a>

                            @if ($component->is_active)
                                <button onclick="toggleStatus('disable')" class="btn btn-danger btn-block">
                                    <i class="fas fa-ban"></i> Nonaktifkan
                                </button>
                            @else
                                <button onclick="toggleStatus('activate')" class="btn btn-success btn-block">
                                    <i class="fas fa-check"></i> Aktifkan
                                </button>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </section>
    </div>
@endsection

@push('scripts')
    <script>
        async function toggleStatus(action) {
            if (!confirm('Yakin ingin mengubah status komponen ini?')) return;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(
                    "{{ url('/api/hrd/components/' . $component->component_id) }}/" + action, {
                        method: 'POST',
                        credentials: 'same-origin', 
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    alert(result.message ?? 'Gagal mengubah status');
                    return;
                }

                alert(result.message ?? 'Berhasil');
                location.reload();

            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan sistem');
            }
        }
    </script>
@endpush
