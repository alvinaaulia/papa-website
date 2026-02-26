@extends('layouts.app-director')

@section('title', 'Riwayat Slip Gaji')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Slip Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Riwayat Slip Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px; margin-bottom: 2rem;">
                        <div class="title-lead">
                            Slip Gaji
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px">
                            Daftar Slip Gaji Karyawan
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header-payroll">
                                <h4>Tabel Slip Gaji</h4>
                            </div>
                            <div class="card-body">

                                <button class="btn btn-primary btn-sm" id="refreshButton" style="margin-bottom: 1rem;">
                                    <i class="fas fa-sync-alt"></i> Refresh Data
                                </button>

                                <div id="emptyState" class="text-center" style="display: none;">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data slip gaji</p>
                                </div>
                                <div id="loading-indicator" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat data...</p>
                                </div>

                                <div id="error-message" class="alert alert-danger d-none">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="error-text"></span>
                                    <button type="button" class="btn btn-sm btn-light ml-2" onclick="retryLoadData()">
                                        <i class="fas fa-redo"></i> Coba Lagi
                                    </button>
                                </div>

                                <div class="table d-none" id="salary-table">
                                    <table class="table table-bordered">
                                        <thead class="overtime-data">
                                            <tr class="text-center">
                                                <th style="width: 5%;">No.</th>
                                                <th style="width: 15%;">Nama Karyawan</th>
                                                <th style="width: 15%;">Tanggal Penyerahan</th>
                                                <th style="width: 10%;">Jumlah</th>
                                                <th style="width: 10%;">Bukti Transfer</th>
                                                <th style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="overtime-data" id="salary-table-body">
                                        </tbody>
                                    </table>
                                </div>

                                <div id="empty-state" class="text-center d-none">
                                    <div class="empty-state" data-height="200">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <h2>Data Slip Gaji Kosong</h2>
                                        <p class="lead">
                                            Belum ada data slip gaji yang tercatat.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Bukti Transfer</h5>
                </div>
                <div class="modal-body text-center">
                    <img id="transfer-proof-image" src="" alt="Bukti Transfer" class="img-fluid rounded shadow-sm"
                        style="max-height: 70vh; object-fit: contain;">
                    <div id="no-proof-message" class="d-none">
                        <i class="fas fa-file-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Bukti transfer tidak tersedia</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="download-proof-link" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="closeFotoModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="{{ asset('js/payslip-history-director.js') }}"></script>
    <script>
        function openFotoModal(proofPath, employeeName) {
            const modalEl = document.getElementById("fotoModal");
            const imageEl = document.getElementById("transfer-proof-image");
            const noProofEl = document.getElementById("no-proof-message");
            const downloadLink = document.getElementById("download-proof-link");
            const modalTitle = document.getElementById("fotoModalLabel");

            modalTitle.textContent = `Bukti Transfer - ${employeeName}`;

            if (proofPath) {
                console.log("Original proofPath:", proofPath);

                const storageUrl = `{{ Storage::url('') }}${proofPath}`;

                console.log("Final image URL:", storageUrl);

                imageEl.src = storageUrl;
                imageEl.classList.remove("d-none");
                noProofEl.classList.add("d-none");

                downloadLink.href = storageUrl;
                downloadLink.classList.remove("d-none");

                imageEl.onerror = function() {
                    console.error("Failed to load image:", storageUrl);
                    imageEl.classList.add("d-none");
                    noProofEl.classList.remove("d-none");
                    downloadLink.classList.add("d-none");

                    showError(
                        "Gagal memuat bukti transfer. File mungkin tidak ditemukan."
                    );
                };

                imageEl.onload = function() {
                    console.log("Image loaded successfully:", storageUrl);
                };
            } else {
                imageEl.classList.add("d-none");
                noProofEl.classList.remove("d-none");
                downloadLink.classList.add("d-none");
            }

            fotoModalInstance = new bootstrap.Modal(modalEl);
            fotoModalInstance.show();
        }

        function closeFotoModal() {
            if (fotoModalInstance) {
                fotoModalInstance.hide();
            }
        }
    </script>
@endpush
