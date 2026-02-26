@extends('layouts.app-director')

@section('title', 'Edit Slip Gaji')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Slip Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-HRD') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Edit Slip Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Edit Penggajian
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                            Isi Formulir Slip Gaji dengan benar!
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST">
                            <div class="card-leave">
                                <div class="card-header-leave">
                                    <h4>Ubah Gaji</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-employee name-employee2">
                                                <label>Nama Karyawan</label>
                                                <select class="employee-input select-employee">
                                                    <option value="" disabled selected>Pilih Karyawan</option>
                                                    <option value="Septian">Septian</option>
                                                    <option value="Abdil">Abdil</option>
                                                    <option value="Chaca">Chaca</option>
                                                    <option value="Yonki">Yonki</option>
                                                </select>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-employee">
                                                <label>Jumlah Gaji</label>
                                                <input type="text" class="employee-input" style="background-color: #fff"
                                                    placeholder="Masukan nominal gaji">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-overtime">
                                <div class="card-header-overtime">
                                    <h4>Status</h4>
                                </div>
                                <div class="card-body">
                                    <div class="overtime-group">
                                        <div class="col-md-14">
                                            <div class="form-employee name-employee2">
                                                <label>Status Kerja</label>
                                                <select class="employee-input select-employee">
                                                    <option value="aktif">Aktif</option>
                                                    <option value="non-aktif">Non Aktif</option>
                                                </select>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overtime-button">
                                <a href="{{ route('payslip-hrd') }}" class="btn btn-light">Batal</a>
                                <a href="{{ route('payslip-hrd') }}" class="btn btn-danger">Buat Slip</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('customScript')
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
@endpush
