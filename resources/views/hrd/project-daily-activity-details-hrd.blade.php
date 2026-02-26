@extends('layouts.app-hrd')

@section('title', 'Rincian Kegiatan Harian Project')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hrd/project-daily-activity-details-hrd.css') }}">

    <style>
        /* Style untuk Select2 border merah */
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
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rincian Kegiatan Harian Project</h1>
            </div>

            <div class="section-body">
                <div class="project-dashboard">
                    <div class="project-info-section mb-4">
                        <div class="project-info-card">
                            <div class="project-header-red">
                                <h2 class="project-name text-center">PAPA WEB</h2>
                            </div>
                            <div class="project-details">
                                <div class="detail-group">
                                    <h3 class="detail-label">Nama Project Manager</h3>
                                    <p class="detail-value">Septian Iqbal Pratama</p>
                                </div>

                                <div class="detail-group">
                                    <h3 class="detail-label">Tenggat Waktu Projek</h3>
                                    <p class="detail-value">02/09/2025</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-section mb-4">
                        <div class="action-container">
                            <button type="button" class="btn btn-filter px-3" id="applyFilter" data-toggle="modal"
                                data-target="#staticBackdrop" style="border-radius: 30px;">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>

                    <div class="project-info-section mb-4">
                        <div class="project-info-card">
                            <div class="project-header-red">
                                <h2 class="project-name text-center">Tabel Rincian Kegiatan Harian Projek</h2>
                            </div>
                            <div class="activities-table-section">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-dark text-center" width="5%">No.</th>
                                                <th class="text-dark text-center" width="50%">Uraian Kegiatan</th>
                                                <th class="text-dark text-center" width="15%">Status</th>
                                                <th class="text-dark text-center" width="15%">Tenggat Waktu</th>
                                                <th class="text-dark text-center" width="15%">Prioritas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span class="status status-not-started-yet">Belum
                                                        Dikerjakan</span></td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-urgent"><i
                                                            class="fas fa-flag text-danger mr-2"></i>Urgent</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span
                                                        class="status status-in-progress">Dikerjakan</span>
                                                </td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-high"><i
                                                            class="fas fa-flag text-primary mr-2"></i>High</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span class="status status-done">Selesai</span>
                                                </td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-clear"><i
                                                            class="fas fa-ban text-secondary mr-2"></i>Clear</span>
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
                            {{-- <div class="form-group">
                                <h5 class="font-weight-bold mb-2">Project</h5>
                                <input type="text" class="form-control" id="projectName"
                                    placeholder="Masukkan nama project" style="border: 1px solid #D51C48;">
                            </div>
                            <div class="form-group mt-3">
                                <label for="status">Status</label>
                                <div>
                                    <span class="status status-done" style="border-radius: 20px">Selesai</span>
                                </div>
                            </div> --}}
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
                <div class="modal-footer border-0 justify-content-end">
                    <button type="button" class="btn btn-danger border" style="width: 120px" data-dismiss="modal">
                        <i class="fas fa-xmark"></i> Batal
                    </button>
                    {{-- <button type="button" class="btn btn-modal-filter" id="applyFilterModal"
                        style="width: 120px">Filter</button> --}}
                    <a href="{{ route('pdf-daily-activity-hrd') }}" class="btn btn-info me-2" target="_blank" style="width: 120px">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            function formatPriority(option) {
                if (!option.id) {
                    return option.text;
                }

                var iconClass = $(option.element).data('icon');
                if (iconClass) {
                    return $(
                        '<span><i class="fas ' + iconClass + ' mr-2"></i>' + option.text + '</span>'
                    );
                }

                return option.text;
            }

            $('#addActivityModal').on('shown.bs.modal', function() {
                $('#activityEmployee').select2({
                    width: '100%',
                    placeholder: 'Pilih Prioritas',
                    allowClear: true,
                    dropdownParent: $('#addActivityModal')
                });

                $('#activityPriority').select2({
                    width: '100%',
                    placeholder: 'Pilih Prioritas',
                    allowClear: true,
                    dropdownParent: $('#addActivityModal'),
                    templateResult: formatPriority,
                    templateSelection: formatPriority
                });

                $('#activityCategory').select2({
                    width: '100%',
                    placeholder: 'Pilih Kategori',
                    allowClear: true,
                    dropdownParent: $('#addActivityModal')
                });
            });

            $('#addActivityModal').on('hidden.bs.modal', function() {
                $('#activityPriority, #activityEmployee, #activityCategory').select2('destroy');
            });
        });
    </script>
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
@endpush
