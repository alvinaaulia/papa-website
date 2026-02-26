@extends('layouts.app-pm')

@section('title', 'Riwayat Kegiatan Harian PM')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/daily-activity-history.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Kegiatan Harian PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-PM') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Riwayat Kegiatan Harian PM</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-daily-activity-pm">Riwayat Kegiatan Harian PM</h2>
                        <p class="section-lead-daily-activity-pm">Daftar Kegiatan Harian</p>
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
                                <h4>Tabel Kegiatan Harian</h4>
                            </div>
                            <hr class="mt-0 mb-0">
                            <div class="card-body">
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered" id="kegiatanTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center" width="30%">No.</th>
                                                <th class="text-center" width="35%">Tanggal</th>
                                                <th class="text-center" width="35%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $activities = [
                                                    ['date' => '01/09/2025'],
                                                    ['date' => '03/09/2025'],
                                                    ['date' => '03/09/2025'],
                                                ];
                                            @endphp

                                            @foreach ($activities as $index => $activity)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td class="text-center">{{ $activity['date'] }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('daily-activity-details-pm', ['date' => $activity['date']]) }}"
                                                            class="btn btn-primary">
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <nav aria-label="Page navigation" class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
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

    <!-- Modal -->
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
                    <a href="{{ route('pdf-daily-activity-pm') }}" class="btn btn-info me-2" target="_blank" style="width: 120px">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                    <button type="button" class="btn btn-success" id="applyFilterModal" style="width: 120px">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
