@extends('layouts.app-director')

@section('title', 'Slip Gaji Direktur')

@push('style')
    <style>
        .payslip-hero {
            border-radius: 18px;
            padding: 24px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 24%),
                linear-gradient(135deg, #111827, #1f2937 48%, #0f766e);
            margin-bottom: 20px;
        }

        .payslip-hero h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .payslip-hero p {
            margin: 0;
            max-width: 760px;
            color: rgba(255,255,255,.86);
            line-height: 1.7;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card,
        .report-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
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

        .report-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .report-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .report-card-subtitle {
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
        }

        .report-card-body {
            padding: 20px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .metric-list {
            display: grid;
            gap: 14px;
        }

        .metric-item {
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .metric-item-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .metric-item-value {
            font-weight: 800;
            color: #0f172a;
            font-size: 22px;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #64748b;
            border-top: 0;
            white-space: nowrap;
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

        @media (max-width: 1100px) {
            .summary-grid,
            .report-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="payslip-hero">
                <h1>Laporan Slip Gaji Direktur</h1>
                <p>
                    Direktur dapat memantau seluruh slip gaji yang sudah tergenerate, melihat bukti transfer,
                    meninjau total pembayaran, dan membaca laporan rekap payroll berdasarkan periode yang dipilih.
                </p>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total Slip</div>
                    <div class="summary-value" id="totalSlipCount">0</div>
                    <div class="summary-note">Seluruh slip gaji yang sudah dibuat</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Pembayaran</div>
                    <div class="summary-value" id="totalPayrollAmount">Rp0</div>
                    <div class="summary-note">Akumulasi nominal slip gaji yang tampil</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Rata-rata Slip</div>
                    <div class="summary-value" id="averagePayrollAmount">Rp0</div>
                    <div class="summary-note">Rata-rata nilai slip per karyawan</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Periode</div>
                    <div class="summary-value" id="selectedPeriodText">Semua</div>
                    <div class="summary-note">Filter laporan aktif saat ini</div>
                </div>
            </div>

            <div class="report-grid">
                <div class="report-card">
                    <div class="report-card-header">
                        <div>
                            <h3 class="report-card-title">Daftar Slip Gaji Tergenerate</h3>
                            <div class="report-card-subtitle">Slip gaji yang sudah dibuat oleh tim HRD.</div>
                        </div>

                        <div class="d-flex align-items-center" style="gap:10px;">
                            <input type="month" id="periodFilter" class="form-control" style="width: 190px;">
                            <button class="btn btn-light" id="refreshButton">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <div class="report-card-body">
                        <div id="errorMessage" class="alert alert-danger d-none"></div>

                        <div class="table-responsive" id="salaryTableWrapper">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Karyawan</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Bukti Transfer</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="salaryTableBody"></tbody>
                            </table>
                        </div>

                        <div id="emptyState" class="empty-state-box d-none">
                            <div><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="font-weight-600 mb-1">Belum ada slip gaji</div>
                            <div>Tidak ada data slip gaji pada periode yang dipilih.</div>
                        </div>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-card-header">
                        <div>
                            <h3 class="report-card-title">Laporan Pembayaran</h3>
                            <div class="report-card-subtitle">Rekap monitoring untuk evaluasi direktur.</div>
                        </div>
                    </div>

                    <div class="report-card-body">
                        <div class="metric-list">
                            <div class="metric-item">
                                <div class="metric-item-label">Slip dengan Bukti Transfer</div>
                                <div class="metric-item-value" id="proofCount">0</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-item-label">Karyawan Unik Dibayarkan</div>
                                <div class="metric-item-value" id="uniqueEmployeeCount">0</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-item-label">Pembayaran Terakhir</div>
                                <div class="metric-item-value" id="latestPaymentText">-</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-item-label">Periode Laporan</div>
                                <div class="metric-item-value" id="reportPeriodText">Semua</div>
                            </div>
                        </div>
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
                    <a id="downloadProofLink" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="closeProofModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script>
        let proofModalInstance;
        let salaryData = [];

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(Number(amount || 0));
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            return new Date(dateString).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });
        }

        function getFilteredData() {
            const period = document.getElementById('periodFilter').value;
            if (!period) {
                return salaryData;
            }

            const [year, month] = period.split('-');
            return salaryData.filter((salary) => {
                const date = new Date(salary.salary_date);
                return String(date.getFullYear()) === year && String(date.getMonth() + 1).padStart(2, '0') === month;
            });
        }

        function updateSummary(data) {
            const totalSlip = data.length;
            const totalAmount = data.reduce((sum, item) => sum + Number(item.salary_amount || 0), 0);
            const averageAmount = totalSlip > 0 ? totalAmount / totalSlip : 0;
            const proofCount = data.filter((item) => item.transfer_proof).length;
            const uniqueEmployeeCount = new Set(data.map((item) => item.employee_name)).size;
            const latestPayment = data.length > 0
                ? [...data].sort((a, b) => new Date(b.salary_date) - new Date(a.salary_date))[0]
                : null;

            const period = document.getElementById('periodFilter').value;
            const periodText = period
                ? new Date(`${period}-01`).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })
                : 'Semua';

            document.getElementById('totalSlipCount').textContent = totalSlip;
            document.getElementById('totalPayrollAmount').textContent = formatCurrency(totalAmount);
            document.getElementById('averagePayrollAmount').textContent = formatCurrency(averageAmount);
            document.getElementById('selectedPeriodText').textContent = periodText;
            document.getElementById('proofCount').textContent = proofCount;
            document.getElementById('uniqueEmployeeCount').textContent = uniqueEmployeeCount;
            document.getElementById('latestPaymentText').textContent = latestPayment
                ? `${latestPayment.employee_name} • ${formatDate(latestPayment.salary_date)}`
                : '-';
            document.getElementById('reportPeriodText').textContent = periodText;
        }

        async function loadSalaryHistory() {
            try {
                document.getElementById('errorMessage').classList.add('d-none');

                const response = await fetch('/api/salary/history');
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal memuat data slip gaji');
                }

                salaryData = result.data || [];
                renderTable();
            } catch (error) {
                const errorEl = document.getElementById('errorMessage');
                errorEl.textContent = error.message;
                errorEl.classList.remove('d-none');
            }
        }

        function renderTable() {
            const data = getFilteredData();
            const tbody = document.getElementById('salaryTableBody');
            const tableWrapper = document.getElementById('salaryTableWrapper');
            const emptyState = document.getElementById('emptyState');

            tbody.innerHTML = '';
            updateSummary(data);

            if (data.length === 0) {
                tableWrapper.classList.add('d-none');
                emptyState.classList.remove('d-none');
                return;
            }

            tableWrapper.classList.remove('d-none');
            emptyState.classList.add('d-none');

            data.forEach((salary) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="font-weight-700 text-dark">${salary.employee_name}</div>
                        <div class="text-muted small">Slip #${salary.id_salary || salary.id}</div>
                    </td>
                    <td>${formatDate(salary.salary_date)}</td>
                    <td class="font-weight-700">${formatCurrency(salary.salary_amount)}</td>
                    <td>
                        ${salary.transfer_proof
                            ? `<a href="#" onclick="openProofModal('${salary.transfer_proof}', '${salary.employee_name}'); return false;">Lihat Bukti</a>`
                            : `<span class="text-muted">Tidak ada</span>`}
                    </td>
                    <td><span class="status-pill status-paid">Generated</span></td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" onclick="handlePrint('${salary.id_salary || salary.id}')">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        async function handlePrint(salaryId) {
            try {
                const response = await fetch(`/api/salary/detail/${salaryId}`);
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Gagal memuat detail slip gaji');
                }

                generatePayslip(result.data);
            } catch (error) {
                alert(error.message);
            }
        }

        function generatePayslip(data) {
            const escapeHtml = (value) => String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');

            const normalizeItems = (items) => Array.isArray(items)
                ? items
                    .map((item) => ({
                        code: String(item.code || ''),
                        name: String(item.name || item.code || '-'),
                        amount: Number(item.amount || 0),
                    }))
                    .filter((item) => item.amount !== 0)
                : [];

            const earningsRaw = normalizeItems(data.earnings);
            const deductionsRaw = normalizeItems(data.deductions);

            const earnings = earningsRaw.filter((item) => item.code !== 'BASIC_SALARY');
            const deductions = [...deductionsRaw];

            const grossSalary = Number(data.gross_salary || 0);
            const fallbackPph21 = Number(data.pph21 || 0);
            const calculatedTotalDeductions = deductions.reduce((sum, item) => sum + item.amount, 0);
            const totalDeductions = Number(
                data.total_deductions ||
                calculatedTotalDeductions ||
                fallbackPph21
            );
            const netSalary = Number(data.net_salary || data.salary_amount || grossSalary - totalDeductions || 0);

            let basicSalary = Number(
                data.calculation_facts?.employee?.basic_salary ||
                data.rule_engine_result?.summary?.basic_salary ||
                0
            );
            if (!basicSalary) {
                const additionalEarnings = earnings.reduce((sum, item) => sum + item.amount, 0);
                basicSalary = Math.max(grossSalary - additionalEarnings, 0);
            }

            if (deductions.length === 0 && fallbackPph21 > 0) {
                deductions.push({
                    code: 'PPH21',
                    name: 'PPh 21',
                    amount: fallbackPph21,
                });
            }

            const earningRows = earnings.length
                ? earnings.map((item) => `
                    <tr>
                        <td>${escapeHtml(item.name)}</td>
                        <td>=</td>
                        <td>${formatCurrency(item.amount).replace('Rp', '').trim()}</td>
                    </tr>
                `).join('')
                : '<tr><td colspan="3">-</td></tr>';

            const deductionRows = deductions.length
                ? deductions.map((item) => `
                    <tr>
                        <td>${escapeHtml(item.name)}</td>
                        <td>=</td>
                        <td>${formatCurrency(item.amount).replace('Rp', '').trim()}</td>
                    </tr>
                `).join('')
                : '<tr><td colspan="3">-</td></tr>';

            const periodDate = new Date(data.salary_date);
            const periodText = periodDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            const paymentDate = periodDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

            const slipHTML = `
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji Karyawan</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #000; margin: 50px; }
        hr { border-top: 2px solid #000; }
        .text-center { text-align: center; }
        .row { display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 3px 0; vertical-align: top; }
        .border-box { border: 1px solid #000; padding: 6px; margin-top: 10px; text-align: center; font-weight: bold; }
        .right { text-align: right; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3><strong>CV Mascitra Teknolog Indonesia</strong></h3>
        <p>Tegal Besar Permai 1, Blk.AB No.9, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68132</p>
        <hr>
        <h4><strong>SLIP GAJI KARYAWAN</strong></h4>
    </div>
    <div style="margin-top: 30px;">
        <table>
            <tr><td>Periode</td><td>:</td><td><strong>${periodText}</strong></td></tr>
            <tr><td>Nama</td><td>:</td><td><strong>${data.employee_name || 'N/A'}</strong></td></tr>
            <tr><td>Tanggal Pembayaran</td><td>:</td><td><strong>${paymentDate}</strong></td></tr>
        </table>
    </div>
    <div class="row" style="margin-top: 30px;">
        <div style="width: 48%;">
            <h4><strong>PENGHASILAN</strong></h4>
            <table>
                <tr><td>Gaji Pokok</td><td>=</td><td>${formatCurrency(basicSalary).replace('Rp', '').trim()}</td></tr>
                ${earningRows}
                <tr><td colspan="2"><strong>Total (A)</strong></td><td><strong>${formatCurrency(grossSalary)}</strong></td></tr>
            </table>
        </div>
        <div style="width: 48%;">
            <h4><strong>POTONGAN</strong></h4>
            <table>
                ${deductionRows}
                <tr><td colspan="2"><strong>Total (B)</strong></td><td><strong>${formatCurrency(totalDeductions)}</strong></td></tr>
            </table>
        </div>
    </div>
    <div class="border-box">PENERIMAAN BERSIH (A-B) = ${formatCurrency(netSalary)}</div>
</body>
</html>`;

            const printWindow = window.open('', '_blank', 'width=900,height=700');
            printWindow.document.open();
            printWindow.document.write(slipHTML);
            printWindow.document.close();
        }

        function openProofModal(proofPath, employeeName) {
            const imageEl = document.getElementById('proofImage');
            const pdfEl = document.getElementById('proofPdf');
            const titleEl = document.getElementById('proofModalLabel');
            const downloadLink = document.getElementById('downloadProofLink');
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
            document.getElementById('periodFilter').addEventListener('change', renderTable);
            document.getElementById('refreshButton').addEventListener('click', loadSalaryHistory);
            loadSalaryHistory();
        });
    </script>
@endpush
