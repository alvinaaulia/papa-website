@extends('layouts.app-director')

@section('title', 'Kegiatan Harian - Projek')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/director/add-project.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Projek</h1>
            </div>

            <div class="section-body">
                <div class="row align-items-right justify-content-end mb-2">
                    <div class="col-md-6 text-md-right text-center">
                        <div class="d-inline-flex"
                            style="gap: 12px;">
                            <button class="btn btn-filter px-3 mr-3"
                                data-toggle="modal" data-target="#addActivityModal" style="width: 60px; height: 60px;">
                                <i class="fas fa-plus" style="font-size: 1.4rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="project-grid">
                            <div class="project-card">
                                <div class="project-header-director">
                                    <h3 class="project-title-director">1. Nama Projek</h3>
                                    <div class="deadline-section">
                                        <span class="deadline-label">deadline projek</span>
                                    </div>
                                </div>
                                <div class="project-content">
                                    <div class="project-info">
                                        <div class="info-row">
                                            <span class="info-label">Tanggal Projek Diberikan :</span>
                                            <div class="input-field">
                                                <input type="text" value="Lorem Ipsum">
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Nama Project Manager :</span>
                                            <div class="input-field">
                                                <input type="text" value="Septian Iqbal Pratama">
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Progress Projekmu :</span>
                                            <div class="progress-card">
                                                <div class="progress-section">
                                                    <span class="progress-text">Progress Kamu</span>
                                                    <div class="progress-stats">
                                                        <span class="progress-percent">75% untuk menyelesaikan</span>
                                                        <span class="progress-complete">1 Minggu</span>
                                                    </div>
                                                    <div class="progress-bar-director">
                                                        <div class="progress-fill" style="width: 75%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="project-footer">
                                        <a href="{{ route('project-daily-activity-details-director') }}"
                                            class="btn btn-filter btn-lg"><span>Detail</span>
                                            <i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="project-card">
                                <div class="project-header-director">
                                    <h3 class="project-title-director">2. Nama Projek</h3>
                                    <div class="deadline-section">
                                        <span class="deadline-label">deadline projek</span>
                                    </div>
                                </div>
                                <div class="project-content">
                                    <div class="project-info">
                                        <div class="info-row">
                                            <span class="info-label">Tanggal Projek Diberikan :</span>
                                            <div class="input-field">
                                                <input type="text" value="Lorem Ipsum">
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Nama Project Manager :</span>
                                            <div class="input-field">
                                                <input type="text" value="Septian Iqbal Pratama">
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Progress Projekmu :</span>
                                            <div class="progress-card">
                                                <div class="progress-section">
                                                    <span class="progress-text">Progress Kamu</span>
                                                    <div class="progress-stats">
                                                        <span class="progress-percent">50% untuk menyelesaikan</span>
                                                        <span class="progress-complete">2 Minggu</span>
                                                    </div>
                                                    <div class="progress-bar-director">
                                                        <div class="progress-fill" style="width: 50%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="project-footer">
                                        <a href="{{ route('project-daily-activity-details') }}"
                                            class="btn btn-filter btn-lg"><span>Detail</span>
                                            <i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addActivityModalTitle">Tambahan Kegiatan Projek</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-description">
                        <p class="description-text">Lengkapi form berikut dengan benar!</p>
                    </div>
                    <form id="activityForm">
                        <div class="form-group">
                            <label for="activityName" class="form-label">Nama Projek</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan nama projek">
                        </div>
                        <div class="form-group">
                            <label for="activityDate" class="form-label">Tanggal Projek Diberikan</label>
                            <input type="text" class="form-control" id="activityDate"
                                placeholder="Masukkan tanggal projek diberikan">
                        </div>
                        <div class="form-group">
                            <label for="activityName" class="form-label">Nama Projek Manager</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan nama projek manager">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        style="width: 5.5rem"><i class="fas fa-xmark"></i> Batal</button>
                    <button type="button" class="btn btn-primary" style="width: 5.5rem"><i class="fas fa-plus"></i> Tambah</button>
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

    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
