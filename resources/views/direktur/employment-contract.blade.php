@extends('layouts.app-director')

@section('title', 'Kontrak Kerja director')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/director/employment-contract.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kontrak Kerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kontrak Kerja</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-employment-director">Kontrak Kerja</h2>
                        <p class="section-lead-employment-director">Lihat Kontrak Kerja</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Kontrak Kerja</h4>
                            </div>
                            <hr class="mt-0 mb-0" style="border-color: #D51C48">
                            <div class="card-body">
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-dark text-center">No.</th>
                                                <th class="text-dark text-center">Nama Karyawan</th>
                                                <th class="text-dark text-center">Tanggal Kontrak</th>
                                                <th class="text-dark text-center">Status</th>
                                                <th class="text-dark text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Jono</td>
                                                <td class="text-center">1 September 2025</td>
                                                <td class="text-center">
                                                    <span class="badge badge-success-director">Selesai</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info"
                                                        style="border-radius: 20px;"data-toggle="modal"
                                                        data-target="#printModalCenter">
                                                        Cetak
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Jamal</td>
                                                <td class="text-center">1 September 2025</td>
                                                <td class="text-center">
                                                    <span class="badge badge-success-director">Selesai</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info"
                                                        style="border-radius: 20px;"data-toggle="modal"
                                                        data-target="#printModalCenter">
                                                        Cetak
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">Dimas</td>
                                                <td class="text-center">1 September 2025</td>
                                                <td class="text-center">
                                                    <span class="badge badge-success-director">Selesai</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info"
                                                        style="border-radius: 20px;"data-toggle="modal"
                                                        data-target="#printModalCenter">
                                                        Cetak
                                                    </button>
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

    <div class="modal fade" id="printModalCenter" tabindex="-1" role="dialog" aria-labelledby="printModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header border-0 align-items-start">
                    <div class="icon-container">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="modal-header-content">
                        <h5 class="modal-title-pm" id="printModalLongTitle">Cetak Kontrak Kerja</h5>
                        <p class="modal-subtitle-pm">Apakah anda yakin untuk mencetaknya?</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div class="form-check" style="margin-left: 54px;">
                        <input class="form-check-input" type="checkbox" id="dontShowAgain">
                        <label class="form-check-label" for="dontShowAgain">
                            Don't show again
                        </label>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-danger text-center" data-dismiss="modal"
                            style="width: 100px"><i class="fas fa-xmark"></i> Batal</button>
                        <a href="{{ route('pdf-employment-contract-director') }}" target="_blank" class="btn btn-info me-2"
                            style="width: 100px">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
    <script src="{{ asset('js/kontrak-kerja.js') }}"></script>
@endpush
