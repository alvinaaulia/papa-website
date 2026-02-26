@extends('layouts.app')

@section('title', 'Kegiatan Harian')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daily-activities.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kegiatan Harian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kegiatan Harian</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-daily-activities">Kegiatan Harian</h2>
                        <p class="section-lead-daily-activities">Daftar Kegiatan Harianmu Sehari-Hari</p>
                    </div>
                    {{-- <div class="col-md-6 text-md-right">
                        <div class="d-inline-flex" style="gap: 12px;">
                            <button type="button" class="btn btn-filter px-3 mr-3" id="applyFilter" data-toggle="modal"
                                data-target="#staticBackdrop" style="border-radius: 30px;">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div> --}}
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <h4>Tabel Kegiatan Harian</h4>
                            </div>
                            <hr class="mt-0 mb-0" style="border-color: #D51C48">
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered" id="kegiatanTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="text-center">No.</th>
                                                <th scope="col" class="text-center">Nama Projek</th>
                                                <th scope="col" class="text-center">Tenggat Waktu</th>
                                                <th scope="col" class="text-center">Nama PM</th>
                                                <th scope="col" class="text-center">Diagram Proses</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Bentuyun</td>
                                                <td class="text-center">03/09/2025</td>
                                                <td class="text-center">Septian</td>
                                                <td class="text-center"><x-bladewind::progress-circle color="red"
                                                        size="100" circle_width="7" percentage="50" text_size="15"
                                                        align="30" valign="0" show_label="true"
                                                        show_percent="true" /></td>
                                                <td class="text-center">
                                                    <a href="{{ route('daily-activity-details-karyawan') }}"><span
                                                            class="btn btn-primary">Rincian</span></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Si Dilan</td>
                                                <td class="text-center">03/09/2025</td>
                                                <td class="text-center">Septian</td>
                                                <td class="text-center"><x-bladewind::progress-circle size="100"
                                                        circle_width="7" percentage="80" text_size="15" align="30"
                                                        valign="0" show_label="true" show_percent="true" /></td>
                                                <td class="text-center">
                                                    <a href="{{ route('daily-activity-details-karyawan') }}"><span
                                                            class="btn btn-primary">Rincian</span></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">Kepegawaian</td>
                                                <td class="text-center">03/09/2025</td>
                                                <td class="text-center">Septian</td>
                                                <td class="text-center"><x-bladewind::progress-circle color="purple"
                                                        size="100" circle_width="7" percentage="70" text_size="15"
                                                        align="30" valign="0" show_label="true"
                                                        show_percent="true" /></td>
                                                <td class="text-center">
                                                    <a href="{{ route('daily-activity-details-karyawan') }}"><span
                                                            class="btn btn-primary">Rincian</span></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Filter -->
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-semibold" id="filterModalLabel">Filter By:</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="filterFormModal">
                        <div class="form-group mb-3">
                            <h6 class="form-label font-weight-bold">Nama Projek</h6>
                            <input type="text" name="nama_projek" class="form-control custom-input"
                                placeholder="Masukkan nama projek">
                        </div>

                        <div class="form-group mb-3">
                            <h6 class="form-label font-weight-bold">Status</h6>
                            <input type="text" name="status" class="form-control custom-input"
                                placeholder="Masukkan status">
                        </div>
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-12 d-flex justify-content-between">
                                    <h6 class="font-weight-bold mb-2">Rentang Tanggal</h6>
                                    <a href="#" class="text-danger text-decoration-none" id="resetFilter">Reset</a>
                                </div>
                            </div>

                            <small class="mt-2">From</small>

                            <div class="d-flex align-items-center">
                                <input type="date" class="form-control" id="fromDate">
                                <span class="mx-2">-</span>
                                <input type="date" class="form-control" id="toDate">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0">
                    <div class="d-flex justify-content-end gap-2 w-100">
                        <button type="button" class="btn btn-danger" style="width: 120px">
                            <i class="fas fa-xmark"></i> Batal
                        </button>
                        <a href="{{ route('daily-activity-pdf-karyawan') }}" class="btn btn-info me-2" style="width: 120px">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('js/page/daily-activities.js') }}"></script>
@endpush
