@extends('layouts.app-director')

@section('title', 'Data Master Jabatan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Master Jabatan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Master Jabatan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="head-approval">
                        <div class="title-overtime-approval">
                            <div class="title-lead">
                                Data Master Jabatan
                            </div>
                            <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                                Daftar Jenis Jabatan Karyawan
                            </div>
                        </div>
                        <div class="approval-filter">
                            <div class="rectangle" data-toggle="modal" data-target="#addPositionModal">
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
                                <h4>Tabel Daftar Jenis Jabatan</h4>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="overtime-data">
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 65%;">Nama Jabatan</th>
                                                <th class="text-center" style="width: 20%">Status</th>
                                                <th class="text-center" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="positionsTableBody">
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyState" class="text-center" style="display: none;">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data jabatan</p>
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
                <button class="modal-close" onclick="positionManager.closeDeleteModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Jenis Jabatan</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus jenis jabatan <strong id="deletePositionName"></strong>?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem"
                    onclick="positionManager.closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem"
                    onclick="positionManager.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPositionModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Jenis Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addPositionForm">
                    @csrf
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <h6 style="margin-bottom: 2rem;">Isi formulir berikut untuk menambah jenis jabatan baru</h6>
                        <div class="form-group">
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nama Jabatan</label>
                                <input type="text" class="form-control" name="position_name"
                                    placeholder="Nama jabatan" required>
                                <div class="invalid-feedback" id="addPositionNameError"></div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Status</label>
                                <select class="select2 form-control" name="status" title="Pilih status" required>
                                    <option value="" selected disabled>Pilih status</option>
                                    <option value="onsite">on site</option>
                                    <option value="online">online</option>
                                    <option value="hybrid">hybrid</option>
                                </select>
                                <div class="invalid-feedback" id="addStatusError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal"
                            aria-label="Close">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-footer">
                            <span id="addSubmitText">Simpan</span>
                            <span id="addLoadingSpinner" class="spinner-border spinner-border-sm" role="status"
                                style="display: none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPositionModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Jenis Jabatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editPositionForm">
                    @csrf
                    <input type="hidden" id="editPositionId" name="id">
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <h6 style="margin-bottom: 2rem;">Isi formulir berikut untuk merubah jenis jabatan</h6>
                        <div class="form-group">
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Nama Jabatan</label>
                                <input type="text" class="form-control" id="editPositionName" name="position_name"
                                    required>
                                <div class="invalid-feedback" id="editPositionNameError"></div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0.8rem;">
                                <label>Status</label>
                                <select class="select2 form-control" id="editStatus" name="status" title="Pilih status"
                                    required>
                                    <option value="" selected disabled>Pilih status</option>
                                    <option value="onsite">on site</option>
                                    <option value="online">online</option>
                                    <option value="hybrid">hybrid</option>
                                </select>
                                <div class="invalid-feedback" id="editStatusError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal"
                            aria-label="Close">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-footer">
                            <span id="editSubmitText">Simpan Perubahan</span>
                            <span id="editLoadingSpinner" class="spinner-border spinner-border-sm" role="status"
                                style="display: none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/DataMaster/master-position.js') }}"></script>
@endpush

@push('customScript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addPositionModal').on('show.bs.modal', function() {
                $('#addPositionForm')[0].reset();
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');

                $('#addPositionModal .select2').select2({
                    dropdownParent: $('#addPositionModal')
                });
            });

            $('#editPositionModal').on('show.bs.modal', function() {
                $('.invalid-feedback').hide();
                $('.is-invalid').removeClass('is-invalid');

                $('#editPositionModal .select2').select2({
                    dropdownParent: $('#editPositionModal')
                });
            });

            $('#addPositionModal .select2').select2({
                dropdownParent: $('#addPositionModal')
            });

            $('#editPositionModal .select2').select2({
                dropdownParent: $('#editPositionModal')
            });
        });
    </script>
@endpush
