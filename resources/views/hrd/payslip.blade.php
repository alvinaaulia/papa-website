@extends('layouts.app-hrd')

@section('title', 'Slip Gaji')

@push('customStyle')
    <style>
        .payslip-hero {
            border-radius: 18px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, .18), transparent 24%),
                linear-gradient(135deg, #991b1b, #b91c1c 46%, #ef4444);
            color: #fff;
            padding: 24px;
            margin-bottom: 20px;
        }

        .payslip-hero h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .payslip-hero p {
            max-width: 760px;
            line-height: 1.7;
            color: rgba(255, 255, 255, .88);
            margin-bottom: 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 20px 0;
        }

        .summary-card,
        .content-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        }

        .summary-card {
            padding: 18px;
        }

        .summary-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
        }

        .summary-note {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .content-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .content-card-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .content-card-subtitle {
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
        }

        .content-card-body {
            padding: 20px;
        }

        .inline-upload {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .inline-upload input[type="file"] {
            max-width: 220px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-awaiting {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .amount-strong {
            font-weight: 700;
            color: #0f172a;
        }

        .empty-state-box {
            text-align: center;
            padding: 42px 20px;
            color: #64748b;
        }

        .empty-state-box i {
            font-size: 30px;
            color: #cbd5e1;
            margin-bottom: 10px;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #64748b;
            border-top: 0;
            white-space: nowrap;
        }

        @media (max-width: 992px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="payslip-hero">
                <h1>Slip Gaji & Validasi Pembayaran</h1>
                <p>
                    Halaman ini menampilkan otomatis daftar karyawan yang menunggu pembayaran gaji. Tim HRD cukup
                    mengunggah bukti transfer, lalu slip gaji akan langsung tergenerate dan masuk ke riwayat slip gaji.
                </p>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Menunggu Pembayaran</div>
                    <div class="summary-value" id="pendingCount">0</div>
                    <div class="summary-note">Karyawan aktif yang belum menerima gaji di periode ini</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Slip Tergenerate</div>
                    <div class="summary-value" id="paidCount">0</div>
                    <div class="summary-note">Slip gaji yang sudah dibuat dari upload bukti transfer</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Periode Pembayaran</div>
                    <div class="summary-value" id="selectedPeriodLabel">-</div>
                    <div class="summary-note">Gunakan filter tanggal untuk mengatur batch pembayaran</div>
                </div>
            </div>

            <div class="content-card mb-4">
                <div class="content-card-header">
                    <div>
                        <h3 class="content-card-title">Karyawan Menunggu Pembayaran</h3>
                        <div class="content-card-subtitle">Upload bukti transfer untuk membuat slip gaji secara otomatis.</div>
                    </div>

                    <div class="d-flex align-items-center" style="gap:10px;">
                        <input type="date" id="paymentDateFilter" class="form-control" style="width: 190px;">
                        <button class="btn btn-light" id="refreshPendingButton">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="content-card-body">
                    <div id="pendingError" class="alert alert-danger d-none"></div>

                    <div class="table-responsive" id="pendingTableWrapper">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Gaji Kotor</th>
                                    <th>PPh 21</th>
                                    <th>Take Home Pay</th>
                                    <th>Status</th>
                                    <th>Bukti Transfer</th>
                                </tr>
                            </thead>
                            <tbody id="pendingTableBody"></tbody>
                        </table>
                    </div>

                    <div id="pendingEmptyState" class="empty-state-box d-none">
                        <div><i class="fas fa-check-circle"></i></div>
                        <div class="font-weight-600 mb-1">Tidak ada pembayaran yang menunggu</div>
                        <div>Semua karyawan aktif pada periode ini sudah memiliki slip gaji.</div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="content-card-header">
                    <div>
                        <h3 class="content-card-title">Riwayat Slip Gaji</h3>
                        <div class="content-card-subtitle">Slip yang sudah tergenerate setelah pembayaran divalidasi.</div>
                    </div>

                    <button class="btn btn-light" id="refreshHistoryButton">
                        <i class="fas fa-sync-alt"></i> Refresh Riwayat
                    </button>
                </div>

                <div class="content-card-body">
                    <div id="historyError" class="alert alert-danger d-none"></div>
                    <div id="historySuccess" class="alert alert-success d-none"></div>

                    <div class="table-responsive" id="historyTableWrapper">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Jumlah Dibayarkan</th>
                                    <th>Bukti Transfer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody"></tbody>
                        </table>
                    </div>

                    <div id="historyEmptyState" class="empty-state-box d-none">
                        <div><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="font-weight-600 mb-1">Belum ada slip gaji</div>
                        <div>Slip akan muncul otomatis setelah bukti transfer diunggah.</div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="proofModalLabel">Bukti Transfer</h5>
                </div>
                <div class="modal-body text-center">
                    <img id="proofImage" src="" alt="Bukti Transfer" class="img-fluid rounded shadow-sm d-none"
                        style="max-height: 70vh; object-fit: contain;">
                    <iframe id="proofPdf" class="w-100 d-none" style="min-height: 70vh; border:0;"></iframe>
                </div>
                <div class="modal-footer">
                    <a id="proofDownloadLink" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeProofModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        let proofModalInstance;
        let pendingEmployees = [];
        let salaryHistory = [];

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(Number(amount || 0));
        }

        function formatShortDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            });
        }

        function setSelectedPeriodLabel(dateString) {
            const targetDate = new Date(dateString);
            document.getElementById('selectedPeriodLabel').textContent = targetDate.toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric',
            });
        }

        function setSummaryCounts() {
            document.getElementById('pendingCount').textContent = pendingEmployees.length;
            document.getElementById('paidCount').textContent = salaryHistory.length;
        }

        async function loadPendingPayments() {
            const date = document.getElementById('paymentDateFilter').value;
            setSelectedPeriodLabel(date);

            try {
                document.getElementById('pendingError').classList.add('d-none');

                const response = await fetch(`/api/salary/pending?salary_date=${encodeURIComponent(date)}`);
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal memuat daftar pembayaran pending');
                }

                pendingEmployees = result.data || [];
                renderPendingTable();
                setSummaryCounts();
            } catch (error) {
                const errorEl = document.getElementById('pendingError');
                errorEl.textContent = error.message;
                errorEl.classList.remove('d-none');
            }
        }

        async function loadSalaryHistory() {
            try {
                document.getElementById('historyError').classList.add('d-none');
                const response = await fetch('/api/salary/history');
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal memuat riwayat slip gaji');
                }

                salaryHistory = result.data || [];
                renderHistoryTable();
                setSummaryCounts();
            } catch (error) {
                const errorEl = document.getElementById('historyError');
                errorEl.textContent = error.message;
                errorEl.classList.remove('d-none');
            }
        }

        function renderPendingTable() {
            const tbody = document.getElementById('pendingTableBody');
            const emptyState = document.getElementById('pendingEmptyState');
            const tableWrapper = document.getElementById('pendingTableWrapper');

            tbody.innerHTML = '';

            if (pendingEmployees.length === 0) {
                tableWrapper.classList.add('d-none');
                emptyState.classList.remove('d-none');
                return;
            }

            tableWrapper.classList.remove('d-none');
            emptyState.classList.add('d-none');

            pendingEmployees.forEach((employee) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="font-weight-700">${employee.employee_name}</div>
                        <div class="text-muted small">Master Salary ID #${employee.id_master_salary}</div>
                    </td>
                    <td class="amount-strong">${formatCurrency(employee.salary_amount)}</td>
                    <td>${formatCurrency(employee.pph21)}</td>
                    <td class="amount-strong">${formatCurrency(employee.net_salary)}</td>
                    <td><span class="status-pill status-awaiting">Menunggu Pembayaran</span></td>
                    <td>
                        <form class="inline-upload pending-upload-form" data-master-salary-id="${employee.id_master_salary}">
                            <input type="hidden" name="salary_date" value="${employee.salary_date}">
                            <input type="file" name="transfer_proof" accept=".jpg,.jpeg,.png,.pdf" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload"></i> Upload & Generate
                            </button>
                        </form>
                    </td>
                `;
                tbody.appendChild(row);
            });

            document.querySelectorAll('.pending-upload-form').forEach((form) => {
                form.addEventListener('submit', handlePendingUpload);
            });
        }

        function renderHistoryTable() {
            const tbody = document.getElementById('historyTableBody');
            const emptyState = document.getElementById('historyEmptyState');
            const tableWrapper = document.getElementById('historyTableWrapper');

            tbody.innerHTML = '';

            if (salaryHistory.length === 0) {
                tableWrapper.classList.add('d-none');
                emptyState.classList.remove('d-none');
                return;
            }

            tableWrapper.classList.remove('d-none');
            emptyState.classList.add('d-none');

            salaryHistory.forEach((salary) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="font-weight-700">${salary.employee_name}</div>
                        <div class="text-muted small">Slip #${salary.id}</div>
                    </td>
                    <td>${formatShortDate(salary.salary_date)}</td>
                    <td class="amount-strong">${formatCurrency(salary.salary_amount)}</td>
                    <td>
                        ${salary.transfer_proof
                            ? `<a href="#" onclick="openProofModal('${salary.transfer_proof}', '${salary.employee_name}'); return false;">Lihat Bukti</a>`
                            : `<span class="text-muted">Tidak ada</span>`}
                    </td>
                    <td>
                        <a href="{{ route('hrd.payslip-details') }}?id=${salary.id}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Rincian
                        </a>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        async function handlePendingUpload(event) {
            event.preventDefault();

            const form = event.currentTarget;
            const masterSalaryId = form.getAttribute('data-master-salary-id');
            const fileInput = form.querySelector('input[name="transfer_proof"]');
            const submitButton = form.querySelector('button[type="submit"]');

            if (!fileInput.files.length) {
                alert('Silakan upload bukti transfer terlebih dahulu.');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses';

            try {
                const formData = new FormData(form);
                formData.append('id_master_salary', masterSalaryId);

                const response = await fetch('/api/salary', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal mengunggah bukti transfer');
                }

                showHistorySuccess(result.message || 'Slip gaji berhasil dibuat otomatis.');
                await Promise.all([loadPendingPayments(), loadSalaryHistory()]);
            } catch (error) {
                alert(error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-upload"></i> Upload & Generate';
            }
        }

        function showHistorySuccess(message) {
            const successEl = document.getElementById('historySuccess');
            successEl.textContent = message;
            successEl.classList.remove('d-none');

            setTimeout(() => {
                successEl.classList.add('d-none');
            }, 3000);
        }

        function openProofModal(proofPath, employeeName) {
            const imageEl = document.getElementById('proofImage');
            const pdfEl = document.getElementById('proofPdf');
            const downloadLink = document.getElementById('proofDownloadLink');
            const titleEl = document.getElementById('proofModalLabel');
            const storageUrl = `{{ Storage::url('') }}${proofPath}`;

            titleEl.textContent = `Bukti Transfer - ${employeeName}`;
            downloadLink.href = storageUrl;

            imageEl.classList.add('d-none');
            pdfEl.classList.add('d-none');

            if (proofPath.toLowerCase().endsWith('.pdf')) {
                pdfEl.src = storageUrl;
                pdfEl.classList.remove('d-none');
            } else {
                imageEl.src = storageUrl;
                imageEl.classList.remove('d-none');
            }

            proofModalInstance = new bootstrap.Modal(document.getElementById('proofModal'));
            proofModalInstance.show();
        }

        function closeProofModal() {
            if (proofModalInstance) {
                proofModalInstance.hide();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('paymentDateFilter').value = today;

            loadPendingPayments();
            loadSalaryHistory();

            document.getElementById('refreshPendingButton').addEventListener('click', loadPendingPayments);
            document.getElementById('refreshHistoryButton').addEventListener('click', loadSalaryHistory);
            document.getElementById('paymentDateFilter').addEventListener('change', loadPendingPayments);
        });
    </script>
@endpush
