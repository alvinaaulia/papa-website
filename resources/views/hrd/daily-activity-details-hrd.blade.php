@extends('layouts.app-hrd')

@section('title', 'Rincian Kegiatan Harian')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daily-activity-details.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rincian Kegiatan Harian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('daily-activities') }}">Kegiatan Harian</a></div>
                    <div class="breadcrumb-item">Rincian Kegiatan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-daily-activities">Rincian Kegiatan Harian</h2>
                        <p class="section-lead-daily-activities">Rincian Kegiatan Harian PM Ada Disini!</p>
                    </div>

                    <div class="col-12">
                        <div class="project-info mb-4">
                            <div class="form-grid">
                                <div class="form-group">
                                    <h6 class="form-label fw-bold">Nama Projek</h6>
                                    <input type="text" name="project_name" class="form-control custom-input"
                                        placeholder="input project name" value="Bentuyun" readonly>
                                </div>

                                <div class="form-group">
                                    <h6 class="form-label fw-bold">Tenggat Waktu Projek</h6>
                                    <input type="text" name="deadlines" class="form-control custom-input"
                                        placeholder="input deadlines" value="03/09/2025" readonly>
                                </div>

                                <div class="form-group">
                                    <h6 class="form-label fw-bold">Nama Project Manager</h6>
                                    <input type="text" name="project_manager_name" class="form-control custom-input"
                                        placeholder="input project manager name" value="Septian" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Tabel Rincian Kegiatan Harian</h4>
                                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                                    <button type="button" class="btn btn-filter px-3" id="applyFilter" data-toggle="modal"
                                        data-target="#staticBackdrop" style="border-radius: 30px;">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>

                            <hr class="mt-0 mb-0" style="border-color: #D51C48">

                            <div class="card-body">
                                <div class="table-responsive mt-2">
                                    <table class="table table-bordered" id="rincianTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" class="text-center" width="5%">No.</th>
                                                <th scope="col" class="text-center" width="45%">Uraian Kegiatan</th>
                                                <th scope="col" class="text-center" width="10%">Status</th>
                                                <th scope="col" class="text-center" width="20%">Tenggat Waktu</th>
                                                <th scope="col" class="text-center" width="20%">Prioritas</th>
                                                {{-- <th scope="col" class="text-center" width="10%">Aksi</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua.</td>
                                                <td class="text-center"><span class="badge badge-secondary-details">Belum
                                                        Dikerjakan</span></td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-urgent"><i
                                                            class="fas fa-flag text-danger mr-2"></i>Urgent</span></td>
                                                {{-- <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-work" style="width: 80px"
                                                        data-toggle="modal" data-target="#workModal"
                                                        data-activity="1">Kerjakan</button>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua.</td>
                                                <td class="text-center"><span
                                                        class="badge badge-info-details">Dikerjakan</span></td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-high"><i
                                                            class="fas fa-flag text-primary mr-2"></i>High</span></td>
                                                {{-- <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-complete"
                                                        style="width: 80px" data-toggle="modal"
                                                        data-target="#completeModal" data-activity="2">Selesai</button>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Lorem Ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua.</td>
                                                <td class="text-center"><span
                                                        class="badge badge-success-details">Selesai</span></td>
                                                <td class="text-center">02/09/2025</td>
                                                <td class="text-center"><span class="priority priority-clear"><i
                                                            class="fas fa-ban text-secondary mr-2"></i>Clear</span>
                                                </td>
                                                {{-- <td class="text-center">
                                                    <span class="text-muted">Selesai</span>
                                                </td> --}}
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

    <!-- Modal Filter -->
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
                                <input type="date" class="form-control" id="fromDate" name="from_date">
                                <span class="mx-2">-</span>
                                <input type="date" class="form-control" id="toDate" name="end_date">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <button type="button" class="btn btn-danger border" style="width: 120px" data-dismiss="modal">
                        <i class="fas fa-xmark"></i> Batal
                    </button>
                    <a href="{{ route('pdf-daily-activity-hrd') }}" target="_blank" class="btn btn-info me-2"
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
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('js/page/daily-activity-details.js') }}"></script> --}}
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
