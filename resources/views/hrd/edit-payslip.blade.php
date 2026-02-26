@extends('layouts.app-hrd')

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
                <div class="breadcrumb-item active">
                    <a href="{{ route('payslip-details-hrd') }}">Rincian Slip Gaji</a>
                </div>
                <div class="breadcrumb-item">Edit Slip Gaji</div>
            </div>
        </div>

        <div class="section-body">
            <div class="sub-head-leave mb-4">
                <div class="title-leave">
                    <div class="title-lead">Slip Gaji</div>
                    <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                        Edit Slip Gaji Karyawan
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    {{-- FORM --}}
                    <form id="payslipForm" data-redirect-url="{{ route('payslip-hrd') }}" enctype="multipart/form-data">

                        {{-- === CARD DETAIL GAJI === --}}
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Detail Slip Gaji</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    {{-- PILIH PEGAWAI --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="color: #d51c48; font-weight: bold">Nama Karyawan</label>
                                            <select id="id_master_salary" name="id_master_salary" class="select2 form-control">
                                                <option value="" disabled selected>Pilih Karyawan</option>
                                            </select>
                                            <div class="invalid-feedback" id="id_master_salary-error"></div>
                                        </div>
                                    </div>

                                    {{-- NOMINAL GAJI --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="color: #d51c48; font-weight: bold">Jumlah Gaji</label>
                                            <input type="text" id="salary_amount" name="salary_amount" class="form-control"
                                                readonly placeholder="Rp 0">
                                            <div class="invalid-feedback" id="salary_amount-error"></div>
                                        </div>
                                    </div>

                                    {{-- TANGGAL --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="salary_date" style="color: #d51c48; font-weight: bold">Tanggal</label>
                                            <input type="date" id="salary_date" name="salary_date" class="form-control" readonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- === CARD UPLOAD BUKTI === --}}
                        <div class="card mt-4">
                            <div class="card-header-overtime">
                                <h4>Bukti Transfer</h4>
                            </div>

                            <div class="card-body">

                                {{-- FILE INPUT --}}
                                <div class="form-group">
                                    <label style="color: #d51c48; font-weight: bold">Upload File Bukti*</label>

                                    <input type="file" id="transfer_proof" name="transfer_proof"
                                        accept=".pdf,.jpg,.jpeg,.png" hidden>

                                    {{-- BOX UPLOAD --}}
                                    <div class="upload-wrapper text-center">

                                        {{-- BEFORE SELECT --}}
                                        <div id="uploadIcon" style="cursor: pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90"
                                                fill="#cfcfcf" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                                                <path
                                                    d="M8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5z" />
                                                <path
                                                    d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2
                                                    2 0 0 1 2-2h5.5L14 4.5z" />
                                            </svg>
                                        </div>

                                        {{-- PREVIEW SETELAH UPLOAD --}}
                                        <div id="filePreview" class="hidden mt-2">

                                            {{-- IMAGE PREVIEW --}}
                                            <img id="imagePreview" class="img-fluid hidden" style="max-height: 200px">

                                            {{-- PDF PREVIEW --}}
                                            <iframe id="pdfPreview" style="width: 100%; height: 300px; display: none"></iframe>

                                            {{-- BUTTON UBAH/HAPUS FILE --}}
                                            <div class="mt-2">
                                                <button type="button" id="changeFile" class="btn btn-warning btn-sm">
                                                    Ganti File
                                                </button>
                                                <button type="button" id="removeFile" class="btn btn-danger btn-sm">
                                                    Hapus File
                                                </button>
                                            </div>

                                        </div>

                                        <div id="fileInfo" class="mt-2"></div>
                                        <div class="invalid-feedback" id="transfer_proof-error"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="overtime-button mt-4">
                            <a href="{{ route('payslip-details-hrd') }}" class="btn btn-light px-4">Batal</a>

                            <button type="submit" id="submitBtn" class="btn btn-danger px-4">
                                <span id="submitText">Simpan Perubahan</span>
                                <span id="submitSpinner" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('customScript')
<script src="{{ asset('js/payslip.js') }}"></script>
@endpush
