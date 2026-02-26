<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Laporan Kegiatan Harian</title>
    <link rel="stylesheet" href="{{ asset('css/hrd/pdf-daily-activity.css') }}">
</head>

<body onload="window.print()">
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="container">

                    <div class="report-container">
                        <div class="report-header">
                            <h1 class="report-title">REKAP LAPORAN KEGIATAN HARIAN</h1>
                            <h3 class="company-name">Mascitra.com</h3>
                            <p class="company-address">
                                Tegal Besar Permai 1, Blk. AB No. 9, Kec. Kaliwates, Kabupaten Jember, Jawa Timur 68132
                            </p>
                        </div>

                        <div class="student-info-container">
                            <div class="info-row-flex">
                                <div class="info-item-flex">
                                    <span class="info-label-custom">Nama</span>
                                    <span class="info-separator-custom">:</span>
                                    <span class="info-value-custom">Anonymous</span>
                                </div>
                                <div class="info-item-flex">
                                    <span class="info-label-custom">Nama Projek</span>
                                    <span class="info-separator-custom">:</span>
                                    <span class="info-value-custom">Bentuyun</span>
                                </div>
                            </div>
                            <div class="info-row-flex">
                                <div class="info-item-flex">
                                    <span class="info-label-custom">NP</span>
                                    <span class="info-separator-custom">:</span>
                                    <span class="info-value-custom">111111111</span>
                                </div>
                                <div class="info-item-flex">
                                    <span class="info-label-custom">Nama Projek Manajer</span>
                                    <span class="info-separator-custom">:</span>
                                    <span class="info-value-custom">Septian Iqbal Pratama</span>
                                </div>
                            </div>
                        </div>

                        <div class="divider-custom"></div>

                        <div class="month-section-custom">
                            <h3 class="month-title-custom">Periode : {{ $from_date ?? '01/09/2025' }} -
                                {{ $end_date ?? '30/10/2025' }}</h3>
                        </div>

                        <div class="table-responsive-custom">
                            <table class="report-table-custom">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hasil Kegiatan (Output)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $startDate = '2025-09-01';
                                        $currentDate = \Carbon\Carbon::parse($startDate);
                                        $getNextDate = function () use (&$currentDate) {
                                            $date = $currentDate->format('d-m-Y');
                                            $currentDate->addDay();
                                            return $date;
                                        };
                                    @endphp
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>mengidentifikasi diagram alir yang akan diterapkan pada sistem kepegawaian
                                            nantinya.</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Membuat ERD dari sistem kepegawaian berdasarkan data dari Flowchart menu yang
                                            sudah dibuat sebelumnya.</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Menyelesaikan ERD dari sistem kepegawaian berdasarkan data dari
                                            Flowchart menu yang sudah dibuat sebelumnya.</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Tidak Mengisi Laporan</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Laravel 10 berhasil terpasang dan siap digunakan untuk tahap
                                            pengembangan aplikasi</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Hari Libur</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Hari Libur</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Berhasil membuat migration untuk tabel Profile, Task Education,
                                            Experience, Project, dan Task sesuai dengan rancangan ERD.</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Menambahkan migration untuk tabel user dan overtime</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Menambahkan field role pada migration user sesuai kebutuhan sistem</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Menyelesaikan slicing untuk section landing page dan dashboard</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Menyelesaikan slicing untuk section landing page dan dashboard</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Hari Libur</td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Hari Libur</td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Berhasil membuat migration untuk tabel Profile, Task Education,
                                            Experience, Project, dan Task sesuai dengan rancangan ERD.</td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>{{ $getNextDate() }}</td>
                                        <td>Laravel 10 berhasil terpasang dan siap digunakan untuk tahap
                                            pengembangan aplikasi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="report-footer-custom">
                            <div class="pagination-info-custom">
                                <p>1 / 11</p>
                            </div>

                            <div class="signature-section-custom">
                                <div class="tanggal-info-custom">
                                    <p><strong>Jember, 16 September 2025</strong></p>
                                    <p>Pembimbing Lapang</p>
                                    <div class="signature-space-custom"></div>
                                    <p><strong>Septian Iqbal Pratama</strong></p>
                                    <p class="e-signature-note-custom">Ditandatangani secara elektronik</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</body>

</html>
