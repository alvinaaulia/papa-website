@extends('layouts.app')

@section('title', 'Slip Gaji')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Slip Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Slip Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px; margin-bottom: 2rem;">
                        <div class="title-lead">Slip Gaji</div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px">Daftar Slip Gaji Kamu</div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header-payroll">
                                <h4>Tabel Slip Gaji</h4>
                            </div>
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Bulan</th>
                                                <th class="text-center">Tahun</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Juli</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info btn-cetak">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Agustus</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info btn-cetak">Cetak</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">September</td>
                                                <td class="text-center">2025</td>
                                                <td class="text-center">
                                                    <button class="btn btn-info btn-cetak">Cetak</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    @include('karyawan.pdf-payslip')
@endpush
