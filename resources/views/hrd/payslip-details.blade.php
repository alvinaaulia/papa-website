@extends('layouts.app-hrd')

@section('title', 'Rincian Slip Gaji')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rincian Slip Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('payslip-hrd') }}">Riwayat Slip Gaji</a></div>
                    <div class="breadcrumb-item">Rincian Slip Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Rincian Slip Gaji
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                            Rincian Slip Gaji Karyawan
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loading-indicator" class="text-center" style="padding: 3rem;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="alert alert-danger d-none">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="error-text"></span>
                    <button type="button" class="btn btn-sm btn-light ml-2" onclick="retryLoadData()">
                        <i class="fas fa-redo"></i> Coba Lagi
                    </button>
                </div>

                <!-- Main Content -->
                <div class="row d-none" id="detail-content">
                    <div class="col-12">
                        <form action="#" method="POST">
                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Detail Slip Gaji</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold">Nama Karyawan</label>
                                                <input type="text" class="form-control" id="employee-name" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tgl" style="color: #d51c48; font-weight: bold">Tanggal
                                                    Penyerahan</label>
                                                <input type="text" class="form-control" id="salary-date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold">Jumlah Gaji
                                                    Dibayarkan</label>
                                                <input type="text" class="form-control total-pay" id="salary-amount"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Informasi Detail Gaji -->
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle"></i> Informasi Gaji:</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small>Gaji Pokok:</small>
                                                        <div class="font-weight-bold" id="gross-salary-display">-</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small>Potongan PPh 21:</small>
                                                        <div class="font-weight-bold" id="pph21-display">-</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small>Take Home Pay (Net):</small>
                                                        <div class="font-weight-bold" id="net-salary-display">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header-overtime">
                                    <h4>Bukti Transfer</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label style="color: #d51c48; font-weight: bold">File Bukti*</label>
                                        <div class="upload-wrapper">
                                            <div class="upload-box" id="proof-container">
                                                <div class="evidence-icon" id="no-proof-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                                                        fill="currentColor" class="bi bi-file-earmark-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="proof-actions" class="mt-3 d-none">
                                            <a id="view-proof-btn" href="#" class="btn btn-info btn-sm"
                                                onclick="openProofModal(); return false;">
                                                <i class="fas fa-eye"></i> Lihat Bukti
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overtime-button">
                                <a href="{{ route('payslip-hrd') }}">
                                    <button type="button" class="btn btn-secondary"
                                        style="padding: 0.5rem 2rem">Kembali</button>
                                </a>
                                <button type="button" class="btn btn-info btn-cetak" id="cetak-button"
                                    style="padding: 0.5rem 2rem">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="proofModalLabel">Bukti Transfer</h5>
                </div>
                <div class="modal-body text-center">
                    <img id="modal-proof-image" src="" alt="Bukti Transfer" class="img-fluid rounded shadow-sm"
                        style="max-height: 70vh; object-fit: contain;">
                </div>
                <div class="modal-footer">
                    <a id="modal-download-link" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="closeProofModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customStyle')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/payslip-details.js') }}"></script>
    <script>
        function openProofModal() {
            if (!currentSalaryData || !currentSalaryData.transfer_proof) {
                return;
            }

            const modalEl = document.getElementById("proofModal");
            const modalImage = document.getElementById("modal-proof-image");
            const modalDownload = document.getElementById("modal-download-link");
            const modalTitle = document.getElementById("proofModalLabel");

            const storageUrl = `{{ Storage::url('') }}${currentSalaryData.transfer_proof}`;

            modalTitle.textContent = `Bukti Transfer - ${currentSalaryData.employee_name}`;
            modalImage.src = storageUrl;
            modalDownload.href = storageUrl;

            proofModalInstance = new bootstrap.Modal(modalEl);
            proofModalInstance.show();
        }

        function closeProofModal() {
            if (proofModalInstance) {
                proofModalInstance.hide();
            }
        }
    </script>
@endpush
