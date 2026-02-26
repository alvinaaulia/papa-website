@extends('layouts.app-hrd')

@section('title', 'Tambah Karyawan Baru')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Karyawan Baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Tambah Karyawan Baru</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Tambah/Undang Karyawan Baru
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                            Tambah/undang lewat sini!
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST">
                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Undang Lewat Email</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold;">Nama Lengkap</label>
                                                <input type="text" class="form-control total-pay" placeholder="Nama Karyawan">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold;">Jabatan</label>
                                                <select class="select2 form-control">
                                                    <option value="option1" disabled selected>Pilih Jabatan</option>
                                                    <option value="option2">Karyawan</option>
                                                    <option value="option3">PM</option>
                                                    <option value="option4">HRD</option>
                                                    <option value="option5">Direktur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold;">Email</label>
                                                <input type="text" class="form-control total-pay" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overtime-button">
                                <a href="{{ route('data-employees-hrd') }}" class="btn btn-danger">Kirim</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('customScript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
