@extends('layouts.app-director')

@section('title', 'Detail Kegiatan Harian PM')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/director/daily-activity-details.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Kegiatan Harian PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Detail Kegiatan Harian PM</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-8">
                        <h2 class="detail-director-title">Detail Kegiatan Harian PM</h2>
                        <p class="detail-director-subtitle">Detail Kegiatan Harian</p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-info" style="border-radius: 30px;" data-toggle="modal"
                            data-target="#cetakLaporanModal">
                            <i class="fas fa-file-pdf mr-2"></i> Cetak Kegiatan Harian
                        </button>
                    </div>
                </div>

                <div class="detail-director-card card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="detail-director-table-title">Detail Kegiatan Harian</h5>
                        <hr class="detail-director-divider">

                        <div class="table-responsive">
                            <table class="detail-director-table table table-bordered text-center align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">No.</th>
                                        <th style="width: 20%">Tanggal</th>
                                        <th style="width: 20%">Nama Project Manager</th>
                                        <th>List Kegiatan Harian</th>
                                        <th style="width: 20%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>04/09/2025</td>
                                        <td>Septian Iqbal Pratama</td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                        <td><span class="detail-director-badge done">Sudah Dikerjakan</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>04/09/2025</td>
                                        <td>Septian Iqbal Pratama</td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                        <td><span class="detail-director-badge done">Sudah Dikerjakan</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>04/09/2025</td>
                                        <td>Septian Iqbal Pratama</td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                        <td><span class="detail-director-badge done">Sudah Dikerjakan</span></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>04/09/2025</td>
                                        <td>Septian Iqbal Pratama</td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                                        <td><span class="detail-director-badge not-done">Belum Dikerjakan</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="cetakLaporanModal" tabindex="-1" role="dialog" aria-labelledby="cetakLaporanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom-modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title-director" id="cetakLaporanModalLabel">Cetak Laporan Kegiatan Harian</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('daily-activity-details') }}" method="GET">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="status">Status</label>
                            <div>
                                <span class="badge-status-daily">Sudah Dikerjakan</span>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <a href="{{ route('pdf-daily-activity-director') }}" target="_blank" class="btn btn-info me-2"
                                style="width: 90px">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
