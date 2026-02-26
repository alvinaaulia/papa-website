@extends('layouts.app-director')

@section('title', 'Data Master Gaji')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Master Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Master Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="head-approval">
                        <div class="title-overtime-approval">
                            <div class="title-lead">
                                Daftar Gaji
                            </div>
                            <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                                Daftar Gaji Karyawan
                            </div>
                        </div>
                        <div class="approval-filter">
                            <div class="rectangle" data-toggle="modal" data-target="#addSalaryModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header-payroll">
                                <h4>Tabel List Gaji Karyawan</h4>
                            </div>
                            <div class="card-body">

                                <div id="successMessage" class="alert alert-success alert-dismissible"
                                    style="display: none;">
                                    <i class="fas fa-check-circle"></i>
                                    <span id="successText"></span>
                                    <button type="button" class="close" onclick="positionManager.hideSuccess()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div id="errorMessage" class="alert alert-danger alert-dismissible" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="errorText"></span>
                                    <button type="button" class="close" onclick="positionManager.hideError()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <button class="btn btn-primary btn-sm" id="refreshButton" style="margin-bottom: 1rem;">
                                    <i class="fas fa-sync-alt"></i> Refresh Data
                                </button>

                                <div class="table">
                                    <table class="table table-bordered" id="salaryTable">
                                        <thead>
                                            <tr class="overtime-data">
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 20%;">Nama Karyawan</th>
                                                <th class="text-center" style="width: 15%;">Nominal Gaji</th>
                                                <th class="text-center" style="width: 10%;">Status</th>
                                                <th class="text-center" style="width: 8%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="salariesTableBody">
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyState" class="text-center" style="display: none;">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data Gaji</p>
                                </div>
                                <div id="loadingIndicator" class="text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-delete-box">
            <div class="modal-icons">
                <div class="modal-trash">
                    <div class="trash-circle"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path
                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                    </svg>
                </div>
                <button class="modal-close" onclick="masterSalary.closeDeleteModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Data Gaji</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus data gaji ini?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem"
                    onclick="salaryManager.closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem"
                    onclick="salaryManager.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSalaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title" id="exampleModalLongTitle">Form Penggajian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addSalaryForm">
                    @csrf
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <h6 style="margin-bottom: 2rem;">Lengkapi form berikut dengan benar!</h6>
                        <div class="form-group">
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nama Karyawan</label>
                                <select class="select2 form-control" id="add_user_id" name="id_user" required>
                                    <option value="" disabled selected>Pilih karyawan</option>
                                </select>
                                <div class="invalid-feedback" id="add_user_error"></div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nominal Gaji (Gross)</label>
                                <input type="text" class="form-control rupiah" id="add_salary_amount"
                                    name="salary_amount" placeholder="Rp0" required
                                    oninput="salaryManager.showPPh21Calculation(this.value)">
                                <small class="form-text text-muted">Gaji sebelum dipotong PPh 21</small>
                                <div class="invalid-feedback" id="add_salary_error"></div>
                            </div>

                            <div id="pph21Preview"></div>

                            <div class="form-group" style="margin-bottom: 0.8rem; margin-top: 1rem;">
                                <label>Status</label>
                                <select class="select2 form-control" id="add_status" name="status"
                                    title="Pilih status">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal"
                            aria-label="Close">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-footer">
                            Simpan dengan Perhitungan PPh 21
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSalaryModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Form Penggajian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editSalaryForm">
                    @csrf

                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <h6 style="margin-bottom: 2rem;">Lengkapi form berikut dengan benar!</h6>
                        <div class="form-group">
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nama Karyawan</label>
                                <select class="select2 form-control" id="edit_user_id" name="id_user" disabled>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nominal Gaji</label>
                                <input type="text" class="form-control rupiah" id="edit_salary_amount"
                                    name="salary_amount" required>
                                <div class="invalid-feedback" id="edit_salary_error"></div>
                            </div>

                            <div id="editPph21Preview"></div>

                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Status</label>
                                <select class="select2 form-control" id="edit_status" name="status"
                                    title="Pilih status">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal"
                            aria-label="Close">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-footer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('customStyle')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('customScript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/DataMaster/master-salary.js') }}"></script>
    <script src="{{ asset('js/DataMaster/payslip-generator.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#addSalaryModal .select2').select2({
                dropdownParent: $('#addSalaryModal')
            });

            $('#editSalaryModal .select2').select2({
                dropdownParent: $('#editSalaryModal')
            })
        });
    </script>
@endpush
