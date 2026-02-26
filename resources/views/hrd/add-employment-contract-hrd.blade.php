@extends('layouts.app-hrd')

@section('title', 'Tambah Kontrak Kerja')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hrd/add-employment-contract-hrd.css') }}">
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

        .select2-container--default .select2-selection--multiple {
            min-height: 40px !important;
            max-height: 90px !important;
            overflow-y: auto;
            border: 1.5px solid #D51C48 !important;
            border-radius: 4px;
        }

        .select2-selection__rendered {
            padding-left: 8px !important;
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }

        .select2-search__field {
            /* css untuk mengatur placeholder agar margin atas bawah jadi 0 */
            margin-top: 10px !important;
            margin-bottom: 0 !important;
            justify-content: start !important;
        }

        .select2-selection__choice {
            /* css untuk mengatur pilihan agar tidak mepet dengan silang nya */
            padding: 0 20px !important;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Kontrak Kerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Tambah Kontrak Kerja</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="title-employment-hrd">Formulir Kontrak Kerja</h2>
                        <p class="subtitle-employment-hrd">Isi formulir kontrak kerja dengan benar!</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-danger text-white fw-bold">
                        Data Pegawai
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employment-contract-hrd') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                {{-- <div class="col-md-6">
                                    <label for="nama" class="form-label fw-semibold">Nama Karyawan</label>
                                    <input type="text" name="nama" id="nama" class="form-control border-danger"
                                        placeholder="Masukkan nama karyawan" required>
                                </div> --}}
                                <div class="col-md-6">
                                    <label for="presenceEmployee" class="form-label fw-semibold">Nama Karyawan</label>
                                    <select name="activtyEmployee[]" class="form-control custom-select select2"
                                        id="activityEmployee" multiple style="border: 1.5px solid #D51C48;">
                                        <option value="1">Karyawan1</option>
                                        <option value="2">Karyawan2</option>
                                        <option value="3">Karyawan3</option>
                                        <option value="4">Karyawan4</option>
                                        <option value="5">Karyawan5</option>
                                        <option value="5">Karyawan6</option>
                                        <option value="5">Karyawan7</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kontrak</label>
                                    <div class="d-flex gap-2">
                                        <input type="date" name="tanggal_mulai" class="form-control border-danger mr-2"
                                            placeholder="Tanggal Mulai" required>
                                        <input type="date" name="tanggal_selesai" class="form-control border-danger"
                                            placeholder="Tanggal Selesai" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light px-4 shadow-sm mr-2">Simpan
                                    Draft</button>
                                <button type="submit" class="btn btn-filter px-4 shadow-sm">Buat Kontrak</button>
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
        $(function() {
            $(document).ready(function() {
                $('#activityEmployee').select2({
                    width: '100%',
                    placeholder: 'Pilih Nama Karyawan',
                    allowClear: true
                });
            })
        });
    </script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
