@extends('layouts.app-pm')

@section('title', 'Laporan Kinerja')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/performance-report.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan Kinerja PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-PM') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Laporan Kinerja PM</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="title-report-pm">Laporan Kinerja PM</h2>
                        <p class="subtitle-report-pm">Daftar Laporan Kinerja</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Laporan Kinerja Per bulan</h4>
                            </div>
                            <hr class="mt-0 mb-0" style="border-color: #D51C48">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Bulan</th>
                                                <th class="text-center">Tahun</th>
                                                <th class="text-center">Nilai Absensi</th>
                                                <th class="text-center">Nilai Kegiatan Harian</th>
                                                <th class="text-center">Rata-rata</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Juli</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                     <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Agustus</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                     <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">September</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
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
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/kegiatan-harian.js') }}"></script>
@endpush
