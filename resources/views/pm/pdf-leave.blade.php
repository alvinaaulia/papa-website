<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-pdf-leave').forEach(button => {
            button.addEventListener('click', () => {
                const slipHTML = `
                <!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Formulir Pengajuan Cuti</title>

    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }

        .table-bordered thead th {
            border-bottom: 1px solid #000 !important;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container" style="padding: 3rem 1rem 3rem 1rem">
        <div class="text-center">
            <h4 style="font-family: Times New Roman, Times, serif;">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</h4>
        </div>
        <div class="data-pegawai" style="padding-top: 1rem">
            <table class="table table-bordered">
                <thead class="table table-bordered">
                    <tr>
                        <th colspan="4">I. DATA PEGAWAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 25%">Nama</td>
                        <td style="width: 25%">Anonymous</td>
                        <td style="width: 25%"></td>
                        <td style="width: 25%"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Jabatan</td>
                        <td style="width: 25%">Karyawan</td>
                        <td style="width: 25%"></td>
                        <td style="width: 25%"></td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Unit Kerja</td>
                        <td colspan="3">Mascitra Tegal Besar</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="jenis-cuti">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4">II. JENIS CUTI YANG DIAMBIL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 25%">1. Cuti Tahunan</td>
                        <td style="width: 25%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                            </svg>
                        </td>
                        <td style="width: 25%">4. Cuti Izin Keperluan Khusus</td>
                        <td style="font-style: italic; width: 25%;">- (dilampiri data dukung)</td>
                    </tr>
                    <tr>
                        <td style="width: 25%">2. Cuti Sakit</td>
                        <td style="font-style: italic; width: 25%;">- (dilampiri surat dokter)</td>
                        <td style="width: 25%">5. Cuti Tanpa Dibayar</td>
                        <td style="font-style: italic; width: 25%;">-</td>
                    </tr>
                    <tr>
                        <td style="width: 25%">3. Cuti Bersalin dan Mendampingi Persalinan</td>
                        <td style="font-style: italic; width: 25%">- (dilampiri data dukung)</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alasan-cuti">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>III. ALASAN CUTI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Acara keluarga di Dubai</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="lama-cuti">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4">IV. LAMANYA CUTI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 25%">Selama</td>
                        <td colspan="3">2 hari kerja</td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Tanggal</td>
                        <td>02/09/2025</td>
                        <td class="text-center">s/d</td>
                        <td>04/00/2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="catatan">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="5">V. CATATAN CUTI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3">1. Cuti Tahunan</td>
                        <td style="width: 20%">2. Cuti Bersalin</td>
                        <td style="width: 20%">-</td>
                    </tr>
                    <tr>
                        <td style="width: 12%">Tahun Kontrak</td>
                        <td style="width: 8%">Sisa Cuti</td>
                        <td style="width: 20%">Keterangan</td>
                        <td style="width: 30%">3. Cuti Bersalin dan Mendampingi Persalinan</td>
                        <td style="width: 30%">-</td>
                    </tr>
                    <tr>
                        <td style="width: 12%">Tahun 1</td>
                        <td style="width: 8%">13</td>
                        <td style="width: 20%">02/09/2025 - 04/09/2025</td>
                        <td style="width: 30%">4. Cuti Izizn Keperluan Khusus</td>
                        <td style="width: 30%"></td>
                    </tr>
                    <tr>
                        <td style="width: 12%">Tahun 2</td>
                        <td style="width: 8%">20</td>
                        <td style="width: 20%">02/09/2025 - 04/09/2025</td>
                        <td style="width: 30%">5. Cuti Tanpa Dibayar</td>
                        <td style="width: 30%">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="alamat">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4">VI. ALAMAT SELAMA CUTI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 25%">Alamat</td>
                        <td colspan="3">Burj Khalifa, Dubai</td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Telepon</td>
                        <td colspan="3">08123456789</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="digital-ttd">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td colspan="2"></td>
                        <td style="width: 25%">
                            <div class="text-center">Hormat saya,</div>
                            <div class="text-center" style="padding: 1rem;">
                                <img src="{{ asset('images/ttd.png') }}" alt="digital-sign" style="height: 9rem; width: 9rem;">
                            </div>
                            <div class="text-center" style="font-weight: bold; text-decoration: underline;">(Anonymous)</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="approval-1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 20%">Disetujui</td>
                        <td style="width: 20%">Perubahan</td>
                        <td style="width: 20%">Ditangguhkan</td>
                        <td style="width: 20%">Keterangan</td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                            </svg>
                        </td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="width: 20%">
                            <div class="text-center">Direktur</div>
                            <div class="text-center" style="padding: 1rem;">
                                <img src="{{ asset('images/ttd.png') }}" alt="digital-sign" style="height: 9rem; width: 9rem;">
                            </div>
                            <div class="text-center" style="font-weight: bold; text-decoration: underline;">(Citra Darma Wida)</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="approval-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="4">VIII. KEPUTUSAN PEJABAT YANG BERWENANG</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="4">A. MANAJER PROYEK</th>
                    </tr>
                    <tr>
                        <td style="width: 20%">Disetujui</td>
                        <td style="width: 20%">Perubahan</td>
                        <td style="width: 20%">Ditangguhkan</td>
                        <td style="width: 20%">Keterangan</td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                            </svg>
                        </td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="width: 20%">
                            <div class="text-center">Manajer Proyek</div>
                            <div class="text-center" style="padding: 1rem;">
                                <img src="{{ asset('images/ttd.png') }}" alt="digital-sign" style="height: 9rem; width: 9rem;">
                            </div>
                            <div class="text-center" style="font-weight: bold; text-decoration: underline;">(Septian Iqbal P.)</div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">B. HRD</th>
                    </tr>
                    <tr>
                        <td style="width: 20%">Disetujui</td>
                        <td style="width: 20%">Perubahan</td>
                        <td style="width: 20%">Ditangguhkan</td>
                        <td style="width: 20%">Keterangan</td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                            </svg>
                        </td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="width: 20%">
                            <div class="text-center">HRD</div>
                            <div class="text-center" style="padding: 1rem;">
                                <img src="{{ asset('images/ttd.png') }}" alt="digital-sign" style="height: 9rem; width: 9rem;">
                            </div>
                            <div class="text-center" style="font-weight: bold; text-decoration: underline;">(Anonymous)</div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="4">C. DIREKTUR</th>
                    </tr>
                    <tr>
                        <td style="width: 20%">Disetujui</td>
                        <td style="width: 20%">Perubahan</td>
                        <td style="width: 20%">Ditangguhkan</td>
                        <td style="width: 20%">Keterangan</td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-check" viewBox="0 0 16 16">
                                <path
                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                            </svg>
                        </td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                        <td style="20%">-</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td style="width: 20%">
                            <div class="text-center">Direktur</div>
                            <div class="text-center" style="padding: 1rem;">
                                <img src="{{ asset('images/ttd.png') }}" alt="digital-sign" style="height: 9rem; width: 9rem;">
                            </div>
                            <div class="text-center" style="font-weight: bold; text-decoration: underline;">(Citra Darma Wida)</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

                `;

                const printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write(slipHTML);
                printWindow.document.close();
            });
        });
    });
</script>
