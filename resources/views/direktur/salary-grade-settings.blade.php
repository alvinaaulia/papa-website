@extends('layouts.app-director')

@section('title', 'Tier/Grade Gaji')

@section('main')
    <div class="main-content grade-master-page">
        <section class="section">
            <div class="section-header">
                <h1>Pengaturan Tier/Grade Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Tier/Grade Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card grade-hero-card border-0 mb-4">
                    <div class="card-body d-flex justify-content-between align-items-start flex-wrap" style="gap: 1rem;">
                        <div>
                            <h2 class="grade-hero-title mb-1">Atur Struktur Tier/Grade Gaji</h2>
                            <p class="grade-hero-subtitle mb-0">
                                Definisikan rentang skor, nominal dasar, dan status grade untuk memastikan penilaian gaji tetap objektif.
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: .6rem;">
                            <a href="{{ route('data-master-payslip') }}" class="btn btn-outline-light btn-sm grade-hero-btn">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Master Gaji
                            </a>
                            <button type="button" class="btn btn-warning btn-sm grade-hero-btn font-weight-600" data-toggle="modal" data-target="#addGradeModal">
                                <i class="fas fa-plus mr-1"></i> Tambah Tier/Grade
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="grade-insight-card">
                            <div class="insight-label">Total Grade</div>
                            <div class="insight-value" id="gradeTotalCount">0</div>
                            <div class="insight-note">Semua tier terdaftar</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="grade-insight-card">
                            <div class="insight-label">Grade Aktif</div>
                            <div class="insight-value text-success" id="gradeActiveCount">0</div>
                            <div class="insight-note">Dipakai pada penilaian</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="grade-insight-card">
                            <div class="insight-label">Cakupan Skor Aktif</div>
                            <div class="insight-value insight-date" id="gradeCoverage">-</div>
                            <div class="insight-note">Rentang min-max aktif</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="grade-insight-card">
                            <div class="insight-label">Rata-rata Gaji Dasar</div>
                            <div class="insight-value insight-date" id="gradeAverageSalary">-</div>
                            <div class="insight-note">Berdasarkan data tampil</div>
                        </div>
                    </div>
                </div>

                <div class="card grade-table-card border-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap: .8rem;">
                        <div>
                            <h4 class="mb-0">Daftar Tier/Grade</h4>
                            <small class="text-muted">Gunakan filter untuk memudahkan evaluasi struktur grade.</small>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: .5rem;">
                            <button class="btn btn-primary btn-sm" id="refreshButton">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                id="gradeSearchInput"
                                placeholder="Cari kode atau nama grade"
                                style="min-width: 220px;">
                            <select class="form-control form-control-sm" id="gradeStatusFilter" style="min-width: 160px;">
                                <option value="all">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Non Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div id="successMessage" class="alert alert-success alert-dismissible" style="display: none;">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span id="successText"></span>
                            <button type="button" class="close" onclick="salaryGradeManager.hideSuccess()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div id="errorMessage" class="alert alert-danger alert-dismissible" style="display: none;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span id="errorText"></span>
                            <button type="button" class="close" onclick="salaryGradeManager.hideError()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="table-responsive grade-table-wrapper">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="overtime-data">
                                        <th class="text-center" style="width: 5%;">No.</th>
                                        <th class="text-center" style="width: 12%;">Kode Tier</th>
                                        <th class="text-center" style="width: 20%;">Nama Grade</th>
                                        <th class="text-center" style="width: 16%;">Rentang Skor</th>
                                        <th class="text-center" style="width: 17%;">Gaji Dasar</th>
                                        <th class="text-center" style="width: 10%;">Status</th>
                                        <th class="text-center" style="width: 20%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="gradesTableBody"></tbody>
                            </table>
                        </div>

                        <div id="emptyState" class="text-center py-5" style="display: none;">
                            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada tier/grade yang sesuai filter.</p>
                        </div>

                        <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Memuat data tier/grade...</p>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                    </svg>
                </div>
                <button class="modal-close" onclick="salaryGradeManager.closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Tier/Grade</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus tier/grade <strong id="deleteGradeName"></strong>?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem" onclick="salaryGradeManager.closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem" onclick="salaryGradeManager.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addGradeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title">Tambah Tier/Grade Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addGradeForm">
                    @csrf
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <div class="form-group">
                            <label>Kode Tier</label>
                            <input type="text" class="form-control" name="grade_code" placeholder="Contoh: TIER-A" required>
                            <div class="invalid-feedback" id="add_grade_code_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Nama Grade</label>
                            <input type="text" class="form-control" name="grade_name" placeholder="Contoh: Senior" required>
                            <div class="invalid-feedback" id="add_grade_name_error"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Skor Minimum</label>
                                    <input type="number" class="form-control" name="min_score" min="0" max="100" required>
                                    <div class="invalid-feedback" id="add_min_score_error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Skor Maksimum</label>
                                    <input type="number" class="form-control" name="max_score" min="0" max="100" required>
                                    <div class="invalid-feedback" id="add_max_score_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gaji Dasar (Gross)</label>
                            <input type="number" class="form-control" name="base_salary" min="0" step="1" required>
                            <div class="invalid-feedback" id="add_base_salary_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Non Aktif</option>
                            </select>
                            <div class="invalid-feedback" id="add_status_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                            <div class="invalid-feedback" id="add_description_error"></div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-footer" id="addSubmitButton">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGradeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title">Edit Tier/Grade Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editGradeForm">
                    @csrf
                    <input type="hidden" id="edit_grade_id" name="id_salary_grade">
                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <div class="form-group">
                            <label>Kode Tier</label>
                            <input type="text" class="form-control" id="edit_grade_code" name="grade_code" required>
                            <div class="invalid-feedback" id="edit_grade_code_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Nama Grade</label>
                            <input type="text" class="form-control" id="edit_grade_name" name="grade_name" required>
                            <div class="invalid-feedback" id="edit_grade_name_error"></div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Skor Minimum</label>
                                    <input type="number" class="form-control" id="edit_min_score" name="min_score" min="0" max="100" required>
                                    <div class="invalid-feedback" id="edit_min_score_error"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Skor Maksimum</label>
                                    <input type="number" class="form-control" id="edit_max_score" name="max_score" min="0" max="100" required>
                                    <div class="invalid-feedback" id="edit_max_score_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gaji Dasar (Gross)</label>
                            <input type="number" class="form-control" id="edit_base_salary" name="base_salary" min="0" step="1" required>
                            <div class="invalid-feedback" id="edit_base_salary_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Non Aktif</option>
                            </select>
                            <div class="invalid-feedback" id="edit_status_error"></div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                            <div class="invalid-feedback" id="edit_description_error"></div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-footer" id="editSubmitButton">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('customStyle')
    <style>
        .grade-master-page {
            --grade-primary: #0f4c81;
            --grade-secondary: #0b3a63;
            --grade-accent: #f59e0b;
            --grade-soft: #eff6ff;
            --grade-border: #dbeafe;
            position: relative;
            font-family: "Poppins", "Segoe UI", sans-serif;
        }

        .grade-master-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 10% 6%, rgba(15, 76, 129, .1), transparent 28%),
                radial-gradient(circle at 85% 18%, rgba(245, 158, 11, .08), transparent 22%);
            pointer-events: none;
        }

        .grade-master-page .section-body {
            position: relative;
            z-index: 1;
        }

        .grade-hero-card {
            background: linear-gradient(140deg, var(--grade-secondary), var(--grade-primary));
            color: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 26px rgba(15, 76, 129, .2);
            animation: gradeFadeUp .45s ease forwards;
        }

        .grade-hero-title {
            font-weight: 700;
            letter-spacing: .2px;
        }

        .grade-hero-subtitle {
            color: rgba(255, 255, 255, .86);
            max-width: 720px;
        }

        .grade-hero-btn {
            border-radius: 999px;
            padding: .45rem .95rem;
        }

        .grade-insight-card {
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--grade-border);
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
            padding: 1rem 1.05rem;
            height: 100%;
            opacity: 0;
            transform: translateY(8px);
            animation: gradeFadeUp .45s ease forwards;
        }

        .grade-master-page .row.mb-2>div:nth-child(1) .grade-insight-card {
            animation-delay: .08s;
        }

        .grade-master-page .row.mb-2>div:nth-child(2) .grade-insight-card {
            animation-delay: .12s;
        }

        .grade-master-page .row.mb-2>div:nth-child(3) .grade-insight-card {
            animation-delay: .16s;
        }

        .grade-master-page .row.mb-2>div:nth-child(4) .grade-insight-card {
            animation-delay: .2s;
        }

        .insight-label {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: .45rem;
        }

        .insight-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: .25rem;
        }

        .insight-date {
            font-size: 1rem;
        }

        .insight-note {
            font-size: .82rem;
            color: #64748b;
        }

        .grade-table-card {
            border-radius: 14px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .07);
            opacity: 0;
            transform: translateY(10px);
            animation: gradeFadeUp .5s ease .24s forwards;
        }

        .grade-table-card .card-header {
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 14px 14px 0 0;
        }

        .grade-table-wrapper table thead th {
            background: var(--grade-soft);
            color: #0f172a;
            border-bottom: 1px solid #bfdbfe;
        }

        .grade-table-wrapper table tbody tr:hover {
            background: #f8fafc;
        }

        .grade-table-wrapper table tbody tr {
            transition: background-color .2s ease;
        }

        @keyframes gradeFadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .grade-hero-title {
                font-size: 1.22rem;
            }

            .grade-table-card .card-header {
                align-items: flex-start !important;
            }

            #gradeSearchInput,
            #gradeStatusFilter {
                min-width: 100% !important;
            }
        }
    </style>
@endpush

@push('customScript')
    <script src="{{ asset('js/DataMaster/salary-grade-settings.js') }}"></script>
@endpush
