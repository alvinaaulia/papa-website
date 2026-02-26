@extends('layouts.app-director')

@section('title', 'Rincian Kegiatan Harian Project')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/director/project-daily-activity-details.css') }}">

    <style>
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
                                                <th class="text-center" width="5%">No.</th>
                                                <th class="text-center" width="40%">Uraian Kegiatan</th>
                                                <th class="text-center" width="20%">Status</th>
                                                <th class="text-center" width="15%">Tenggat Waktu</th>
                                                <th class="text-center" width="15%">Prioritas</th>
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
                            </div> --}}
                            {{-- <div class="form-group mt-3">
                                <label for="status">Status</label>
                                <div>
                                    <span class="status status-done">Selesai</span>
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
                    <button type="button" class="btn btn-danger border" data-dismiss="modal" style="width: 120px"><i
                            class="fas fa-xmark"></i> Batal</button>
                    {{-- <button type="button" class="btn btn-modal-filter" id="applyFilterModal"
                        style="width: 120px">Filter</button> --}}
                    <a href="{{ route('pdf-daily-activity-director') }}" target="_blank" class="btn btn-info me-2"
                        style="width: 120px">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>
        </div>
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
                        <h5 class="modal-title-pm" id="printModalLongTitle">Cetak Laporan Kegiatan Harian HRD</h5>
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
                        <button type="button" class="btn btn-secondary text-center"
                            data-dismiss="modal">Kembali</button>
                        <button type="button" class="btn btn-filter text-center">Simpan</button>
                    </div>
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
    <script>
        $(document).ready(function() {
            // Data contoh untuk kegiatan
            const activities = {
                1: {
                    description: "Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                    deadline: "02/09/2025",
                    priority: "Urgent"
                },
                2: {
                    description: "Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                    deadline: "02/09/2025",
                    priority: "High"
                }
            };

            // Modal Kerjakan
            $('.btn-work').on('click', function() {
                const activityId = $(this).data('activity');
                const activity = activities[activityId];

                if (activity) {
                    $('#workActivityDescription').text(activity.description);
                    $('#workActivityDeadline').text(activity.deadline);
                    $('#workActivityPriority').text(activity.priority);
                }
            });

            // Modal Selesai
            $('.btn-complete').on('click', function() {
                const activityId = $(this).data('activity');
                const activity = activities[activityId];

                if (activity) {
                    $('#completeActivityDescription').text(activity.description);
                    $('#completeActivityDeadline').text(activity.deadline);
                    $('#completeActivityPriority').text(activity.priority);

                    // Set tanggal dan waktu saat ini
                    const now = new Date();
                    const today = now.toISOString().split('T')[0];
                    const time = now.toTimeString().split(' ')[0].substring(0, 5);

                    $('#completionDate').val(today);
                    $('#completionTime').val(time);
                }
            });

            // Konfirmasi Kerjakan
            $('#confirmWork').on('click', function() {
                const button = $(this);
                const originalText = button.html();

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

                // Simulasi proses
                setTimeout(function() {
                    button.prop('disabled', false).html(originalText);
                    $('#workModal').modal('hide');

                    // Tampilkan notifikasi sukses
                    showNotification(
                        'Pekerjaan berhasil dimulai! Status diubah menjadi "Dikerjakan"',
                        'success');

                    // Di sini Anda bisa menambahkan logika untuk update status di database
                }, 2000);
            });

            // Konfirmasi Selesai
            $('#confirmComplete').on('click', function() {
                const button = $(this);
                const fileInput = $('#completionProof')[0];
                const notes = $('#completionNotes').val();

                // Validasi file
                if (!fileInput.files.length) {
                    showNotification('Harap pilih bukti penyelesaian!', 'error');
                    return;
                }

                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (file.size > maxSize) {
                    showNotification('Ukuran file terlalu besar! Maksimal 5MB.', 'error');
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                if (!allowedTypes.includes(file.type)) {
                    showNotification('Format file tidak didukung! Harus JPG, PNG, PDF, atau DOC.', 'error');
                    return;
                }

                const originalText = button.html();
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupload...');

                // Simulasi upload
                setTimeout(function() {
                    button.prop('disabled', false).html(originalText);
                    $('#completeModal').modal('hide');

                    // Reset form
                    $('#completeActivityForm')[0].reset();

                    // Tampilkan notifikasi sukses
                    showNotification('Pekerjaan berhasil diselesaikan! Bukti telah diupload.',
                        'success');

                    // Di sini Anda bisa menambahkan logika untuk update status di database
                }, 3000);
            });

            // Drag and drop untuk file upload
            const fileUploadArea = $('.file-upload-area');
            const fileInput = $('#completionProof');

            fileUploadArea.on('dragover', function(e) {
                e.preventDefault();
                fileUploadArea.addClass('dragover');
            });

            fileUploadArea.on('dragleave', function(e) {
                e.preventDefault();
                fileUploadArea.removeClass('dragover');
            });

            fileUploadArea.on('drop', function(e) {
                e.preventDefault();
                fileUploadArea.removeClass('dragover');
                const files = e.originalEvent.dataTransfer.files;
                fileInput[0].files = files;

                // Trigger change event
                fileInput.trigger('change');
            });

            fileInput.on('change', function() {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    fileUploadArea.html(`<i class="fas fa-file-upload text-success fa-2x mb-2"></i><br>
                               <strong>File terpilih:</strong> ${fileName}<br>
                               <small class="text-muted">Klik untuk mengganti file</small>`);
                }
            });

            // Fungsi notifikasi
            function showNotification(message, type) {
                // Anda bisa menggunakan library notifikasi seperti Toastr
                // atau implementasi notifikasi custom
                alert(message); // Sementara pakai alert, bisa diganti dengan notifikasi yang lebih baik
            }

            // Filter functionality
            $('#applyFilterModal').on('click', function() {
                const fromDate = $('#fromDate').val();
                const toDate = $('#toDate').val();

                if (fromDate && toDate) {
                    // Implementasi filter berdasarkan tanggal
                    console.log('Filter dari:', fromDate, 'sampai:', toDate);
                    $('#staticBackdrop').modal('hide');
                    showNotification('Filter berhasil diterapkan!', 'success');
                } else {
                    showNotification('Harap pilih rentang tanggal!', 'error');
                }
            });

            // Reset filter
            $('#resetFilter').on('click', function(e) {
                e.preventDefault();
                $('#fromDate').val('');
                $('#toDate').val('');
            });
        });
    </script>
@endpush
