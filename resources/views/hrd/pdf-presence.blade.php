<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Rekap Absensi - {{ $nama_karyawan ?? 'Anonymous' }}</title>
    <link rel="stylesheet" href="{{ asset('css/hrd/pdf-presence.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href={{ asset('css/direktur/pdf-presence.css') }}>
</head>

<body onload="window.print()">
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="presence-container">
                                <div class="presence-header">
                                    <h1 class="company-name">REKAP ABSENSI</h1>
                                    <h3 class="company-subtitle">Mascitra.com</h3>
                                    <p class="company-address">
                                        Tegal Besar Permai 1, Blk. AB No. 9, Kec. Kaliwates, Kabupaten Jember, Jawa
                                        Timur
                                        68132
                                    </p>
                                </div>

                                <div class="student-info-aligned">
                                    <div class="info-item-aligned">
                                        <span class="info-label-aligned">Nama</span>
                                        <span class="info-separator-aligned">:</span>
                                        <span class="info-value-aligned">{{ $nama_karyawan ?? 'Anonymous' }}</span>
                                    </div>
                                    <div class="info-item-aligned">
                                        <span class="info-label-aligned">NIM/NIK</span>
                                        <span class="info-separator-aligned">:</span>
                                        <span class="info-value-aligned">232410101010</span>
                                    </div>
                                    <div class="info-item-aligned">
                                        <span class="info-label-aligned">Jabatan/Divisi</span>
                                        <span class="info-separator-aligned">:</span>
                                        <span class="info-value-aligned">UI/UX Designer</span>
                                    </div>
                                    <div class="info-item-aligned">
                                        <span class="info-label-aligned">Periode Laporan</span>
                                        <span class="info-separator-aligned">:</span>
                                        <span class="info-value-aligned">{{ $bulan ?? 'September' }}
                                            {{ $tahun ?? '2025' }}</span>
                                    </div>
                                </div>

                                <div class="divider-line"></div>

                                <div class="month-section">
                                    <h3 class="month-title">Bulan : {{ $bulan ?? 'September' }} {{ $tahun ?? '2025' }}
                                    </h3>
                                </div>

                                <div class="table-responsive">
                                    <table class="presence-table">
                                        <thead
                                            style="background-color: #D51C48 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Hari</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Jumlah Jam</th>
                                                <th>Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $days = [
                                                    'Minggu',
                                                    'Senin',
                                                    'Selasa',
                                                    'Rabu',
                                                    'Kamis',
                                                    'Jumat',
                                                    'Sabtu',
                                                ];
                                                $total_hadir = 0;
                                                $total_tidak_hadir = 0;
                                            @endphp

                                            @for ($i = 1; $i <= 30; $i++)
                                                @php
                                                    $date = \Carbon\Carbon::create(
                                                        $tahun ?? 2025,
                                                        array_search($bulan ?? 'September', [
                                                            'Januari',
                                                            'Februari',
                                                            'Maret',
                                                            'April',
                                                            'Mei',
                                                            'Juni',
                                                            'Juli',
                                                            'Agustus',
                                                            'September',
                                                            'Oktober',
                                                            'November',
                                                            'Desember',
                                                        ]) + 1,
                                                        $i,
                                                    );
                                                    $dayIndex = $date->dayOfWeek;
                                                    $isWeekend = $dayIndex == 0 || $dayIndex == 6;
                                                    $isPresent = !$isWeekend && $i <= 22;

                                                    if ($isPresent) {
                                                        $total_hadir++;
                                                    }
                                                    if (!$isPresent && !$isWeekend) {
                                                        $total_tidak_hadir++;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $days[$dayIndex] }}</td>
                                                    <td>
                                                        @if ($isPresent)
                                                            07:48:23 WIB
                                                        @elseif($isWeekend)
                                                            <em>Libur</em>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="{{ !$isPresent ? 'status-notyet' : '' }}">
                                                        @if ($isPresent && $i % 3 == 0)
                                                            Belum Absen Pulang
                                                        @elseif($isPresent)
                                                            16:30:15 WIB
                                                        @elseif($isWeekend)
                                                            <em>Libur</em>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($isPresent && $i % 3 != 0)
                                                            8 jam 42 menit
                                                        @elseif($isWeekend)
                                                            <em>Libur</em>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="presence-status">
                                                        @if ($isWeekend)
                                                            <span style="color: #6c757d;">Libur</span>
                                                        @elseif($isPresent)
                                                            <span style="color: #28a745;">Hadir</span>
                                                        @else
                                                            <span style="color: #dc3545;">Tidak Hadir</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <table style="width: 100%">
                                    <tr>
                                        <td style="width: 30%;">
                                            <div class="summary-section">
                                                <table class="summary-table">
                                                    <tbody>
                                                        <tr
                                                            style="background-color: #D51C48 !important; color: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                            <td><strong>Kehadiran</strong></td>
                                                            <td><strong>Jumlah</strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hadir</td>
                                                            <td>{{ $total_hadir }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tidak Hadir</td>
                                                            <td>{{ $total_tidak_hadir }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Hari Libur</td>
                                                            <td>{{ 30 - $total_hadir - $total_tidak_hadir }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        <td style="40%"></td>
                                        <td style="width: 30%;">
                                            <div class="signature-section">
                                                <p>Jember, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                                                <p>Hormat Kami,</p>
                                                <br><br><br>
                                                <p><strong>{{ $nama_karyawan ?? 'Anonymous' }}</strong></p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
