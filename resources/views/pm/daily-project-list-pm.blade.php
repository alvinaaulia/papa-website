@extends('layouts.app-pm')

@section('title', 'Kegiatan Harian - Projek')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/daily-project-list.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Projek</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="project-grid">
                            <div class="project-card">
                                <div class="project-header-pm">
                                    <h3 class="project-title-pm">1. Nama Projek</h3>
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
                                            <span class="info-label">Nama PM :</span>
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
                                                    <div class="progress-bar-pm">
                                                        <div class="progress-fill" style="width: 75%"></div>
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

                            <div class="project-card">
                                <div class="project-header-pm">
                                    <h3 class="project-title-pm">2. Nama Projek</h3>
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
                                             <span class="info-label">Nama PM :</span>
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
                                                    <div class="progress-bar-pm">
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
