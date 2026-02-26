@extends('layouts.app-pm')

@section('title', 'Rincian Kegiatan Harian Project')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/project-daily-activity-details.css') }}">

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

        /* css untuk mengatur select2 single dan tanda silang saat inputan dipilih dropdown
                 agar posisi nya sesuai */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: 30px !important;
            top: 2px !important;
        }

        /* css untuk mengatur select2 single dan tanda panah dropdown
                 agar posisi nya sesuai */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            right: 8px !important;
            top: 2px !important;
            position: absolute;
        }

        /* css untuk mengatur select2 multiple agar tinggi atau lebar otomatis mengikuti inputan */
        .select2-container--default .select2-selection--multiple {
            min-height: 38px !important;
            max-height: 90px !important;
            overflow-y: auto;
            border: 1.5px solid #D51C48 !important;
            border-radius: 4px;
        }

        .select2-search__field {
            /* css untuk mengatur placeholder agar margin atas bawah jadi 0 */
            margin-top: 10px !important;
            margin-bottom: 0 !important;
            justify-content: start !important;
        }

        .select2-selection__choice {
            /* css untuk mengatur pilihan agar tidak mepet dengan silang nya */
            padding: 0 20px !important;
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
                                <h2 class="project-name text-center">Nama Projek</h2>
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
                                data-target="#staticBackdrop" style="border-radius: 20px;">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-filter" data-toggle="modal" data-target="#addActivityModal"
                                style="border-radius: 20px;">
                                <i class="fas fa-plus"></i> Add
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
                                                <th class="text-center" width="5%">No.</th>
                                                <th class="text-center" width="30%">Uraian Kegiatan</th>
                                                <th class="text-center" width="15%">Status</th>
                                                <th class="text-center" width="15%">Tenggat Waktu</th>
                                                <th class="text-center" width="10%">Prioritas</th>
                                                <th class="text-center" width="10%">Aksi</th>
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
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-work" style="width: 80px"
                                                        data-toggle="modal" data-target="#workModal"
                                                        data-activity="1">Kerjakan</button>
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
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-complete"
                                                        style="width: 80px" data-toggle="modal" data-target="#completeModal"
                                                        data-activity="2">Selesai</button>
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
                                            {{-- <tr>
                                                <td class="text-center">3</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span
                                                        class="status status-testing-review">Pengujian</span></td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-normal"><i
                                                            class="fas fa-flag text-success mr-2"></i>Normal</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="" class="btn btn-revisi btn-secondary text-dark"
                                                        style="padding: 0.2rem 1.3rem; border-radius: 20px;">Edit</a>
                                                </td>
                                            </tr> --}}
                                            {{-- <tr>
                                                <td class="text-center">5</td>
                                                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pretium
                                                    nulla
                                                    bicus, placerat suscipit ex rhoncus eu.</td>
                                                <td class="text-center"><span class="status status-revision">Revisi</span>
                                                </td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-clear"><i
                                                            class="fas fa-ban text-secondary mr-2"></i>Clear</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="" class="btn btn-revisi btn-secondary text-dark"
                                                        style="padding: 0.2rem 1.3rem; border-radius: 20px;">Edit</a>
                                                </td>
                                            </tr> --}}
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

    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #D51C48; border-radius: 10px 10px 0 0;">
                    <h5 class="modal-title text-white" id="addActivityModalLabel">Tambah Tasklist</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addActivityForm">
                        <div class="form-section">
                            <h6 class="section-title">Uraian Kegiatan Harian</h6>
                            <div class="form-group">
                                <textarea class="custom-textarea" id="activityDescription" rows="4" style="border: 1.5px solid #D51C48;"
                                    placeholder="Masukkan uraian kegiatan harian"></textarea>
                            </div>
                        </div>
                        <div class="divider-horizontal"></div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="activityDeadline" class="form-label">Tenggat Waktu</label>
                                <input type="text" name="daterange" class="form-control custom-input"
                                    id="activityDeadline" style="border: 1.5px solid #D51C48;">
                            </div>

                            <div class="form-group">
                                <label for="activityPriority" class="form-label">Prioritas</label>
                                <select class="form-control custom-select select2" id="activityPriority">
                                    <option value="">Pilih Prioritas</option>
                                    <option value="urgent" data-icon="fa-flag text-danger">Urgent</option>
                                    <option value="high" data-icon="fa-flag text-primary">High</option>
                                    <option value="normal" data-icon="fa-flag text-success">Normal</option>
                                    <option value="low" data-icon="fa-flag text-muted">Low</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="activityEmployee" class="form-label">Karyawan</label>
                                <select class="form-control custom-select select2" id="activityEmployee"
                                    style="border: 1.5px solid #D51C48;">
                                    <option></option>
                                    <option value="karyawan1">Septian Iqbal Pratama</option>
                                    <option value="karyawan2">Nama Karyawan Lain</option>
                                    <option value="karyawan3">Karyawan Contoh</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="activityCategory" class="form-label">Kategori</label>
                                <select class="form-control custom-select select2" id="activityCategory"
                                    multiple="multiple" style="border: 1.5px solid #D51C48;">
                                    <option value="">Pilih Kategori</option>
                                    <option value="development">Development</option>
                                    <option value="design">Design</option>
                                    <option value="testing">Testing</option>
                                    <option value="documentation">Documentation</option>
                                    <option value="meeting">Meeting</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-save btn-filter" id="saveActivity">Simpan</button>
                </div>
            </div>
        </div>
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
                    <a href="{{ route('pdf-daily-activity-pm') }}" class="btn btn-info me-2" target="_blank"
                        style="width: 120px">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                    <button type="button" class="btn btn-success" id="applyFilterModal" style="width: 120px">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
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
                                <small class="form-text text-muted" style="margin-right: 45px">Format: JPG, PNG, PDF, DOC
                                    (Maks. 5MB)</small>
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
                    placeholder: 'Pilih Karyawan',
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
    <script src="{{ asset('library/select2/dist/js/select2.min.js') }}"></script>
@endpush
