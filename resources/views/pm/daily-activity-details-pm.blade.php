@extends('layouts.app-pm')

@section('title', 'Detail Kegiatan Harian PM')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/daily-activity-details.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Kegiatan Harian PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-PM') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('daily-activity-history-pm') }}">Riwayat Kegiatan
                            Harian</a></div>
                    <div class="breadcrumb-item">Detail Kegiatan Harian</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="page-title-details">Detail Kegiatan Harian PM</h2>
                        <p class="page-subtitle-details">Detail Kegiatan Harian</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Kegiatan Harian</h4>
                            </div>
                            <hr class="mt-0 mb-0" style="border-color: #D51C48">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">No.</th>
                                                <th class="text-center" width="15%">Tanggal</th>
                                                <th class="text-center" width="45%">List Kegiatan Harian</th>
                                                <th class="text-center" width="20%">Status</th>
                                                <th class="text-center" width="15%">Prioritas</th>
                                                <th class="text-center" width="20%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">02/09/2025</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span class="status status-not-started-yet">Belum
                                                        Dikerjakan</span></td>
                                                <td class="text-center"><span class="priority priority-urgent"><i
                                                            class="fas fa-flag text-danger mr-2"></i>Urgent</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-work" style="width: 80px"
                                                        data-toggle="modal" data-target="#workModal"
                                                        data-activity="1">Kerjakan</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">02/09/2025</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span
                                                        class="status status-in-progress">Dikerjakan</span>
                                                </td>
                                                <td class="text-center"><span class="priority priority-high"><i
                                                            class="fas fa-flag text-primary mr-2"></i>High</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-complete"
                                                        style="width: 80px" data-toggle="modal" data-target="#completeModal"
                                                        data-activity="2">Selesai</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">02/09/2025</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span class="status status-done">Selesai</span>
                                                </td>
                                                <td class="text-center"><span class="priority priority-clear"><i
                                                            class="fas fa-ban text-secondary mr-2"></i>Clear</span>
                                                </td>
                                            </tr>
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
    <div class="modal fade" id="workModal" tabindex="-1" aria-labelledby="workModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-semibold" id="workModalLabel">Konfirmasi Pengerjaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-play-circle text-info fa-3x mb-3"></i>
                        <h6 class="font-weight-bold">Apakah Anda yakin ingin memulai pekerjaan ini?</h6>
                        <p class="text-muted">Status akan berubah menjadi "Dikerjakan"</p>
                    </div>
                    <div class="activity-info">
                        <div class="info-item">
                            <span class="info-label">Uraian Kegiatan:</span>
                            <span class="info-value" id="workActivityDescription">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tenggat Waktu:</span>
                            <span class="info-value" id="workActivityDeadline">-</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Prioritas:</span>
                            <span class="info-value" id="workActivityPriority">-</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-end">
                    <button type="button" class="btn btn-secondary" style="width: 120px" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-info" style="width: 120px" id="confirmWork">
                        <i class="fas fa-play"></i> Mulai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-semibold" id="completeModalLabel">Selesaikan Pekerjaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h6 class="font-weight-bold">Upload Bukti Penyelesaian</h6>
                        <p class="text-muted">Lampirkan bukti bahwa pekerjaan telah selesai</p>
                    </div>

                    <form id="completeActivityForm">
                        <div class="activity-info mb-4">
                            <div class="info-item">
                                <span class="info-label">Uraian Kegiatan:</span>
                                <span class="info-value" id="completeActivityDescription">-</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tenggat Waktu:</span>
                                <span class="info-value" id="completeActivityDeadline">-</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Prioritas:</span>
                                <span class="info-value" id="completeActivityPriority">-</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="completionProof" class="form-label fw-bold">Bukti Penyelesaian</label>
                            <div class="file-upload-area">
                                <input type="file" class="form-control-file" id="completionProof"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                <small class="form-text text-muted" style="margin-right: 45px">Format: JPG, PNG, PDF, DOC (Maks. 5MB)</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="completionNotes" class="form-label fw-bold">Catatan Penyelesaian</label>
                            <textarea class="form-control" id="completionNotes" rows="3"
                                placeholder="Tambahkan catatan mengenai penyelesaian pekerjaan ini..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label fw-bold">Waktu Penyelesaian</label>
                            <div class="d-flex align-items-center">
                                <input type="date" class="form-control mr-2" id="completionDate">
                                <input type="time" class="form-control" id="completionTime">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 justify-content-end">
                    <button type="button" class="btn btn-secondary" style="width: 120px" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-success" style="width: 120px" id="confirmComplete">
                        <i class="fas fa-check"></i> Selesai
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

    <script src="{{ asset('js/pm/daily-activity-details.js') }}"></script>
@endpush
