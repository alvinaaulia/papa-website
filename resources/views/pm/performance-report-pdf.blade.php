<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kinerja - Cetak</title>
    <link rel="stylesheet" href="{{ asset('css/pm/performance-report-pdf.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>REKAP EVALUASI KINERJA KARYAWAN</h1>
        </div>

        <!-- Informasi Karyawan -->
        <div class="employee-info">
            <table class="info-table">
                <tr>
                    <td width="120"><strong>Nama</strong></td>
                    <td width="20">:</td>
                    <td><strong id="employee-name">Achmad Rifai</strong></td>
                </tr>
                <tr>
                    <td><strong>Jabatan</strong></td>
                    <td>:</td>
                    <td id="employee-position">Karyawan</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>:</td>
                    <td id="employee-status">Aktif</td>
                </tr>
                <tr>
                    <td><strong>Periode Evaluasi</strong></td>
                    <td>:</td>
                    <td><strong id="evaluation-period">01-10-2024 s/d 31-10-2025</strong></td>
                </tr>
                <tr>
                    <td><strong>Bulan</strong></td>
                    <td>:</td>
                    <td><strong id="report-month">Juli 2025</strong></td>
                </tr>
                <tr>
                    <td><strong>Tipe</strong></td>
                    <td>:</td>
                    <td id="report-type">Onsite</td>
                </tr>
            </table>
        </div>

        <!-- Ringkasan Evaluasi -->
        <div class="section">
            <h2>Ringkasan Evaluasi Kinerja</h2>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Komponen Penilaian</th>
                        <th>Keterangan</th>
                        <th width="100">Nilai Rata-rata</th>
                        <th width="100">Predikat</th>
                    </tr>
                </thead>
                <tbody id="summary-body">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3"><strong>NILAI AKHIR (RATA-RATA KESELURUHAN):</strong></td>
                        <td><strong id="final-score">83.33</strong></td>
                        <td><strong id="final-predicate">Baik</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Detail Evaluasi -->
        <div class="section">
            <h2>Detail Evaluasi per Tanggal</h2>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="100">Tanggal</th>
                        <th width="120">Nilai Harian</th>
                        <th width="120">Nilai Projek</th>
                        <th width="100">Rata-rata</th>
                        <th width="100">Predikat</th>
                    </tr>
                </thead>
                <tbody id="detail-body">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Keterangan Skor -->
        <div class="score-info">
            <h3>Keterangan Skor:</h3>
            <table class="score-table">
                <tr>
                    <td><strong>Sangat Baik</strong></td>
                    <td>90 - 100</td>
                </tr>
                <tr>
                    <td><strong>Baik</strong></td>
                    <td>80 - 90</td>
                </tr>
                <tr>
                    <td><strong>Cukup</strong></td>
                    <td>70 - 80</td>
                </tr>
                <tr>
                    <td><strong>Buruk</strong></td>
                    <td>60 - 70</td>
                </tr>
                <tr>
                    <td><strong>Sangat Buruk</strong></td>
                    <td>&lt; 60</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <div class="footer-left">
                    <p id="print-date"></p>
                    <p id="print-time"></p>
                </div>
                <div class="footer-right">
                    <p><strong>PT Moda Tronsoft Perkasa</strong></p>
                    <p id="supervisor-name">Dinda Izzabillah Febriyanti</p>
                </div>
            </div>
            <div class="page-info">
                <span id="page-info">1/1</span>
            </div>
        </div>
    </div>

    <script src="performance-report-print.js"></script>
</body>
</html>
