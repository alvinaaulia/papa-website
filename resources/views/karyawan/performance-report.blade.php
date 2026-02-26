@extends('layouts.app')

@section('title', 'Laporan Kinerja')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
                <h1>Laporan Kinerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Laporan Kinerja</div>
                </div>
        </div>
        <div class="sub-head-leave">
                <div class="title-leave">
                    <div class="title-lead">
                       Laporan Kinerja
                    </div>
                    <div class="sub-lead" style="font-size: 1rem; padding-left: 20px;">
                        Laporan kinerja kamu per bulan
                    </div>
                </div>
        </div>
        <div class="col-12">
                        <div class="card">
                            <div class="card-header-payroll">
                                <h4>Tabel Laporan Kinerja Per Bulan</h4>
                            </div>
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 10%;">Bulan</th>
                                                <th class="text-center" style="width: 10%;">Tahun</th>
                                                <th class="text-center" style="width: 10%;">Nilai Absensi</th>
                                                <th class="text-center" style="width: 12%;">Nilai Kegiatan Harian</th>
                                                <th class="text-center" style="width: 8%;">Rata-rata</th>
                                                <th class="text-center" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Juli</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">96</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Agustus</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">96</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">September</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">96</td>
                                                <td class="text-center">100</td>
                                                <td class="text-center">98</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info" style="border-radius: 20px">Cetak</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
    </section>
</div>
@endsection
