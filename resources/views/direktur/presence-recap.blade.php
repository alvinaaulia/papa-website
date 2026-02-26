@extends('layouts.app-director')

@section('title', 'Laporan Rekap Absensi')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/director/presence-recap.css') }}">
    <style>
        .form-label {
            color: #D51C48 !important;
        }

        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            border: 1.5px solid #D51C48 !important;
            border-radius: 4px;
            height: 38px;
        }

        .select2-container .select2-selection--multiple {
            height: auto;
            min-height: 38px;
        }

        .select2-container--focus .select2-selection--single,
        .select2-container--focus .select2-selection--multiple {
            border-color: #D51C48 !important;
            box-shadow: 0 0 0 0.2rem rgba(213, 28, 72, 0.25) !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 8px;
            color: #495057;
        }

        .select2-selection--multiple {
            border: 1.5px solid #D51C48 !important;
            border-radius: 4px;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-selection__rendered {
            padding-left: 8px !important;
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .select2-search__field {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .select2-selection__choice {
            padding: 0 20px !important;
        }

        .presence-recap-input {
            border: 1.5px solid #D51C48 !important;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Rekap Absensi</h1>
            </div>

            <div class="section-body">
                <h2 class="presence-recap-director-title">Laporan Rekap Absensi</h2>
                <p class="presence-recap-director-subtitle">Laporan rekap absensi karyawan</p>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('pdf-presence-director') }}" method="GET" target="_blank">
                            <div class="presence-recap-form-row">
                                <div class="presence-recap-form-group">
                                    <label for="nama_karyawan" class="presence-recap-label fw-semibold">Nama
                                        Karyawan</label>
                                    <select multiple class="form-control custom-select select2" id="activtyEmployee"
                                        name="nama_karyawan" data-placeholder="pilih nama karyawan">
                                        <option></option>
                                        <option value="Adinda Tri Dinanti">Adinda Tri Dinanti</option>
                                        <option value="Febiana">Febiana</option>
                                        <option value="Anonymous">Anonymous</option>
                                    </select>
                                </div>

                                <div class="presence-recap-form-group">
                                    <label class="presence-recap-label">Bulan</label>
                                    <select class="form-control custom-select select2" name="bulan">
                                        <option value="">Pilih Bulan</option>
                                        <option value="Januari">Januari</option>
                                        <option value="Februari">Februari</option>
                                        <option value="Maret">Maret</option>
                                        <option value="April">April</option>
                                        <option value="Mei">Mei</option>
                                        <option value="Juni">Juni</option>
                                        <option value="Juli">Juli</option>
                                        <option value="Agustus">Agustus</option>
                                        <option value="September" selected>September</option>
                                        <option value="Oktober">Oktober</option>
                                        <option value="November">November</option>
                                        <option value="Desember">Desember</option>
                                    </select>
                                </div>

                                <div class="presence-recap-form-group">
                                    <label class="presence-recap-label">Tahun</label>
                                    <select class="form-control custom-select select2" name="tahun">
                                        <option value="2024">2024</option>
                                        <option value="2025" selected>2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                            </div>
                            <div class="presence-recap-action">
                                <button type="submit" class="btn btn-filter">Cetak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih opsi',
                allowClear: true
            });
        });
    </script>
@endpush
