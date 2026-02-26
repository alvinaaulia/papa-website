@extends('layouts.app-hrd')

@section('title', 'Tambah Slip Gaji')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/payslip-upload.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Slip Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Tambah Slip Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Formulir Slip Gaji
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                            Isi Formulir Slip Gaji dengan benar!
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form id="payslipForm" enctype="multipart/form-data" data-redirect-url="{{ route('payslip-hrd') }}">
                            @csrf
                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Tambah Slip Gaji</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold">Nama Karyawan *</label>
                                                <select class="select2 form-control" id="id_master_salary"
                                                    name="id_master_salary" required>
                                                    <option value="" disabled selected>Pilih Karyawan</option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    Pilih karyawan yang akan menerima gaji
                                                </small>
                                                <div class="invalid-feedback" id="id_master_salary-error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="salary_date" style="color: #d51c48; font-weight: bold">Tanggal Pembayaran *</label>
                                                <input type="date" class="form-control" id="salary_date"
                                                    name="salary_date" style="background-color: #fff" required>
                                                <small class="form-text text-muted">
                                                    Tanggal gaji akan dibayarkan
                                                </small>
                                                <div class="invalid-feedback" id="salary_date-error"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="salaryDetails" class="d-none"></div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label style="color: #d51c48; font-weight: bold">Jumlah yang Dibayarkan (Net) *</label>
                                                <input type="text" class="form-control" id="salary_amount"
                                                    name="salary_amount" style="background-color: #f8f9fa"
                                                    placeholder="Jumlah gaji bersih yang dibayarkan" readonly>
                                                <small class="form-text text-muted">
                                                    Jumlah ini adalah gaji bersih setelah dipotong PPh 21
                                                </small>
                                                <div class="invalid-feedback" id="salary_amount-error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header-overtime">
                                    <h4>Bukti Transfer</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label style="color: #d51c48; font-weight: bold">Upload Bukti Transfer *</label>
                                        <div class="upload-wrapper">
                                            <div class="upload-box">
                                                <div class="evidence-icon" id="uploadIcon">
                                                    <label for="transfer_proof">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="80"
                                                            height="80" fill="currentColor"
                                                            class="bi bi-file-earmark-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2z" />
                                                        </svg>
                                                        <div class="upload-text text-center" style="margin-top: 1rem;">
                                                            <h4>Upload bukti transfer disini</h4>
                                                            <p>Format: PDF, JPG, PNG (Maks. 2MB)</p>
                                                        </div>
                                                    </label>
                                                    <input type="file" id="transfer_proof" name="transfer_proof"
                                                        accept=".jpg,.jpeg,.png,.pdf" required hidden>
                                                </div>
                                                <div class="preview-container hidden" id="filePreview">
                                                    <img id="imagePreview" class="file-preview hidden" alt="Preview Gambar">
                                                    <iframe id="pdfPreview" class="file-preview hidden"></iframe>
                                                    <div class="file-info" id="fileInfo"></div>
                                                    <div class="preview-actions">
                                                        <button type="button" class="btn-change" id="changeFile">Ganti
                                                            File</button>
                                                        <button type="button" class="btn-remove"
                                                            id="removeFile">Hapus</button>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback" id="transfer_proof-error"></div>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            *Upload bukti transfer pembayaran gaji
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="overtime-button">
                                <a href="{{ route('payslip-hrd') }}" class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-danger" id="submitBtn">
                                    <span id="submitText">Buat Slip Gaji</span>
                                    <div id="submitSpinner" class="spinner-border spinner-border-sm d-none"
                                        role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/payslip.js') }}"></script>
@endpush