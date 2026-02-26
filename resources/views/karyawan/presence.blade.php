@extends('layouts.app')

@section('title', 'Absensi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/presence.css') }}">
@endpush

@section('main')

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Absensi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Absensi</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-absensi">Absensiku</h2>
                        <p class="section-lead-absensi">Daftar Absensi Masuk Dan Pulang</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <div class="d-inline-flex" style="gap: 12px;">
                            <button type="button" class="btn btn-filter px-3 mr-3" id="applyFilter" data-toggle="modal"
                                data-target="#staticBackdrop" style="border-radius: 30px;">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Absen Masuk & Pulang</h4>
                                <div class="card-header-form">
                                    <form>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search">
                                            <div class="input-group-btn">
                                                <button class="btn btn-filter"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr class="mt-0 mb-0" style="border-color: #D51C48">
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered" id="kegiatanTable">
                                        <thead>
                                            <tr>
                                                <th class="text-dark text-center">No.</th>
                                                <th class="text-dark text-center">Tanggal</th>
                                                <th class="text-dark text-center">Jam Masuk</th>
                                                <th class="text-dark text-center">Jam Pulang</th>
                                                <th class="text-dark text-center">Jumlah Jam</th>
                                                <th class="text-dark text-center">Info Masuk</th>
                                                <th class="text-dark text-center">Info Pulang</th>
                                                <th class="text-dark text-center">Status</th>
                                                <th class="text-dark text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <tr>
                                                    <td class="text-dark text-center">{{ $i }}</td>
                                                    <td class="text-dark text-center">01/10/2025</td>
                                                    <td class="text-dark text-center">07:49:32</td>
                                                    <td class="text-dark text-center">Belum Absen Pulang</td>
                                                    <td class="text-dark text-center">-</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm-absen btn-info-custom"
                                                            data-toggle="modal" data-target="#OpenFotoModal">
                                                            Buka Foto
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm-absen btn-info-custom"
                                                            data-toggle="modal" data-target="#OpenFotoModal">
                                                            Buka Foto
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="text-dark">Hadir</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown show">
                                                            <a class="btn btn-primary dropdown-toggle" href="#"
                                                                role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                Detail
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="fas fa-arrow-right"></i> Lihat Lokasi Masuk
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="fas fa-arrow-left"></i> Lihat Lokasi Pulang
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <nav class="d-inline-block">
                                    <ul class="pagination mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"><i
                                                    class="fas fa-chevron-left"></i></a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1 <span
                                                    class="sr-only">(current)</span></a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <div class="form-group">
                            <div class="row align-items-center">
                                <div class="col-12 d-flex justify-content-between">
                                    <h5 class="font-weight-bold mb-2">Rentang Tanggal</h5>
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
                <div class="modal-footer border-0 justify-content-between">
                    <button type="button" class="btn btn-danger border" style="width: 120px">
                        <i class="fas fa-xmark"></i> Batal
                    </button>
                    <a href="{{ route('pdf-presence-karyawan') }}" class="btn btn-info me-2" target="_blank" style="width: 120px">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                    <button type="button" class="btn btn-success" id="applyFilterModal" style="width: 120px">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="OpenFotoModal" tabindex="-1" role="dialog" aria-labelledby="OpenFotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="OpenFotoModal">Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>

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
    <script src="{{ asset('js/page/absensi.js') }}"></script>
@endpush
