@extends('layouts.app-pm')

@section('title', 'Laporan Kegiatan Harian')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/pm/daily-activity-report.css') }}">
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

        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 50px !important;
            margin-top: 9px !important;
            top: 2px !important;
        }

        /* css untuk mengatur select2 single dan tanda panah dropdown
                                                                                            agar posisi nya sesuai */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            right: 8px !important;
            top: 2px !important;
            position: absolute;
        }

        .select2-selection--multiple {
            border: 1.5px solid #D51C48 !important;
            border-radius: 4px;
            height: auto !important;
        }

        .select2-selection__rendered {
            padding-left: 8px !important;
            padding-top: 4px !important;
            padding-bottom: 4px !important;
        }


        .select2-search__field {
            /* css untuk mengatur placeholder agar margin atas bawah jadi 0 */
            margin-top: 9px !important;
            margin-bottom: 0 !important;
        }

        .select2-selection__choice {
            /* css untuk mengatur pilihan agar tidak mepet dengan silang nya */
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
                <h1>Laporan Kegiatan Harian</h1>
            </div>

            <div class="section-body">
                <h2 class="section-title-print-report-pm">Laporan kegiatan harian</h2>
                <p class="section-lead-print-report-pm">Laporan kegiatan harian kamu</p>

                <div class="card shadow-sm laporan-card">
                    <div class="card-body">
                        <form action="{{ route('daily-activity-report-pm') }}" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="activityEmployee" class="form-label fw-semibold">Nama
                                        Karyawan</label>
                                    <select name="activtyEmployee[]" class="form-control custom-select select2"
                                        id="activityEmployee" multiple>
                                        <option></option>
                                        <option value="1">Adinda Tri Dinanti</option>
                                        <option value="2">Alvina Aulia Nisa</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="activityProject" class="form-label fw-semibold">Projek</label>
                                    <select class="form-control custom-select select2" id="activityPoject">
                                        <option selected disabled>Pilih projek</option>
                                        <option value="1">Website PAPA</option>
                                        <option value="2">Sertifikasi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control border-danger">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control border-danger">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('pdf-daily-activity-pm') }}" class="btn btn-filter"
                                    style="border-radius: 20px">
                                    Cetak {{-- <i class="fas fa-print"></i>  --}}
                                </a>
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

                $('#activityProject').select2({
                    width: '100%',
                    placeholder: 'Pilih Project',
                    allowClear: true
                });
            });

        });
    </script>
    <script>
        document.querySelectorAll('.btn-cetak').forEach(button => {
            button.addEventListener('click', () => {
                const row = this.closest('tr');
                const bulan = row.cells[1].textContent.trim();
                const tahun = row.cells[2].textContent.trim();

                window.open('/daily-activity-pdf/cetak/${bulan}/${tahun}', '_blank');
            });

            // const printWindow = window.open('', '_blank');
            // printWindow.document.open();
            // printWindow.document.write(slipHTML);
            // printWindow.document.close();
        });
    </script>
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}">
        < /scrip> <
        script src = "{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}" >
    </script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.min.js') }}"></script>
@endpush
