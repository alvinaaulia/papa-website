@extends('layouts.app-pm')

@section('title', 'Formulir Pengajuan Lembur')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengajuan Lembur</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-PM') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Pengajuan Lembur</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-overtime">
                    <div class="title-overtime">
                        <div class="title-lead">
                            Formulir Pengajuan Lembur
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px;">
                            Daftar pengajuan lemburmu di sini!
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST">
                            <div class="card">
                                <div class="card-header-overtime">
                                    <h4>Waktu Lembur</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="overtime-custom" style="padding: 0">
                                            <div class="overtime-time">
                                                <label for="tgl" style="font-weight: bold">Tanggal Lembur*</label>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" id="tgl-mulai" name="tanggal_mulai">
                                                </div>
                                            </div>
                                            <div class="input-time-overtime">
                                                <div class="overtime-time">
                                                    <label for="jamMulai" style="font-weight: bold">Jam Lembur Mulai*</label>
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="jamMulai" name="jam_mulai">
                                                    </div>
                                                </div>
                                                <div class="overtime-time">
                                                    <label for="jamAkhir" style="font-weight: bold">Jam Lembur Berakhir*</label>
                                                    <div class="form-group">
                                                        <input type="time" class="form-control" id="jamAkhir" name="jam_akhir">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header-overtime">
                                    <h4>Deskripsi Lembur</h4>
                                </div>
                                <div class="card-body">
                                    <div class="overtime-group">
                                        <label style="font-weight: bold">Uraian kegiatan lembur*</label>
                                        <textarea class="form-control" name="alasan" style="height: 120px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header-overtime">
                                    <h4>Bukti Lembur</h4>
                                </div>
                                <div class="card-body">
                                    <div class="overtime-group">
                                        <label style="font-weight: bold">File Bukti*</label>
                                        <div class="upload-wrapper">
                                            <div class="upload-box" style="display: flex; flex-direction: column;">
                                                <div class="evidence-icon">
                                                    <label for="inputEvidence">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="80"
                                                            height="80" fill="currentColor"
                                                            class="bi bi-file-earmark-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2z" />
                                                        </svg>
                                                        <div class="upload-text text-center" style="margin-top: 1rem">
                                                            <h4>Upload your file(s) here</h4>
                                                            <p>Support: PDF, IMG (Max file 2MB)</p>
                                                        </div>
                                                    </label>
                                                    <input type="file" id="inputEvidence"
                                                        multiple hidden>


                                                    {{-- <label for="payslipfile"><svg xmlns="http://www.w3.org/2000/svg"
                                                            width="100" height="100" fill="#d1d1d1"
                                                            class="bi bi-file-plus" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5z" />
                                                            <path
                                                                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1" />
                                                        </svg></label>
                                                    <input type="file" id="payslipfile" accept=".pdf,.jpg,.jpeg,.png"
                                                        multiple hidden> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overtime-button">
                                <a href="{{ route('overtime-histories-pm') }}" class="btn btn-light">Batal</a>
                                <a href="{{ route('overtime-histories-pm') }}" class="btn btn-danger">Simpan</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
@endpush
