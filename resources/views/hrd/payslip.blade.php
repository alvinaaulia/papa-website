@extends('layouts.app-hrd')

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

                                <div id="success-message" class="alert alert-success d-none">
                                    <i class="fas fa-check-circle"></i>
                                    <span id="success-text"></span>
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
                                                <th style="width: 15%;">Aksi</th>
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
                    {{-- <button type="button" class="btn-close" onclick="closeFotoModal()"></button> --}}
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

    <div id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true"
        class="modal-overlay">
        <div class="modal-delete-box">
            <div class="modal-icons">
                <div class="modal-trash">
                    <div class="trash-circle"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path
                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                    </svg>
                </div>
                <button class="modal-close" onclick="closeDeleteModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Pengajuan Cuti</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus pengajuan cuti ini?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem"
                    onclick="closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem"
                    id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="{{ asset('js/payslip-history-hrd.js') }}"></script>
    <script>
        function displaySalaryData(data) {
            const tableBody = document.getElementById('salary-table-body');
            const tableContainer = document.getElementById('salary-table');
            const emptyState = document.getElementById('empty-state');
            const loadingIndicator = document.getElementById('loading-indicator');

            loadingIndicator.classList.add('d-none');

            if (data.length === 0) {
                tableContainer.classList.add('d-none');
                emptyState.classList.remove('d-none');
                return;
            }

            tableContainer.classList.remove('d-none');
            emptyState.classList.add('d-none');

            tableBody.innerHTML = '';

            data.forEach((salary, index) => {
                const row = document.createElement('tr');
                row.className = 'text-center';

                const formattedDate = formatDate(salary.salary_date);
                const formattedAmount = formatCurrency(salary.salary_amount);

                const hasProof = salary.transfer_proof !== null && salary.transfer_proof !== '';
                const proofButtonText = hasProof ? 'Buka foto' : 'Tidak ada';
                const proofButtonClass = hasProof ? 'evidence' : 'text-muted';
                const proofButtonStyle = hasProof ? 'cursor: pointer; text-decoration: none' :
                    'cursor: default; text-decoration: none';

                row.innerHTML = `
                <td>${index + 1}</td>
                <td>${salary.employee_name}</td>
                <td>${formattedDate}</td>
                <td>${formattedAmount}</td>
                <td>
                    <a href="#" class="${proofButtonClass}"
                       style="${proofButtonStyle}"
                       ${hasProof ? `onclick="openFotoModal('${salary.transfer_proof}', '${salary.employee_name}')"` : 'onclick="return false;"'}>
                        ${proofButtonText}
                    </a>
                </td>
                <td>
                    <div class="btn-group" style="gap: 0.5rem;">
                        <span>
                            <a href="{{ route('payslip-details-hrd') }}?id=${salary.id}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Rincian
                            </a>
                        </span>
                        <span>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(${salary.id}, '${salary.employee_name}')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </span>
                    </div>
                </td>
            `;

                tableBody.appendChild(row);
            });
        }

        function openFotoModal(proofPath, employeeName) {
            const modalEl = document.getElementById('fotoModal');
            const imageEl = document.getElementById('transfer-proof-image');
            const noProofEl = document.getElementById('no-proof-message');
            const downloadLink = document.getElementById('download-proof-link');
            const modalTitle = document.getElementById('fotoModalLabel');

            modalTitle.textContent = `Bukti Transfer - ${employeeName}`;

            if (proofPath) {
                console.log('Original proofPath:', proofPath);
                const storageUrl = `{{ Storage::url('') }}${proofPath}`;
                console.log('Final image URL:', storageUrl);

                imageEl.src = storageUrl;
                imageEl.classList.remove('d-none');
                noProofEl.classList.add('d-none');

                downloadLink.href = storageUrl;
                downloadLink.classList.remove('d-none');

                imageEl.onerror = function() {
                    console.error('Failed to load image:', storageUrl);
                    imageEl.classList.add('d-none');
                    noProofEl.classList.remove('d-none');
                    downloadLink.classList.add('d-none');

                    showError('Gagal memuat bukti transfer. File mungkin tidak ditemukan.');
                };

                imageEl.onload = function() {
                    console.log('Image loaded successfully:', storageUrl);
                };

            } else {
                imageEl.classList.add('d-none');
                noProofEl.classList.remove('d-none');
                downloadLink.classList.add('d-none');
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
