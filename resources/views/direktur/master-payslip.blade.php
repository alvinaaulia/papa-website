@extends('layouts.app-director')

@section('title', 'Data Master Gaji')

@section('main')
    <div class="main-content salary-master-page">
        <section class="section">
            <div class="section-header">
                <h1>Data Master Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Master Gaji</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card salary-hero-card border-0 mb-4">
                    <div class="card-body d-flex justify-content-between align-items-start flex-wrap" style="gap: 1rem;">
                        <div>
                            <h2 class="salary-hero-title mb-1">Strategi Penetapan Gaji Karyawan</h2>
                            <p class="salary-hero-subtitle mb-0">
                                Kelola data master gaji, tier/grade, dan periode gaji agar proses payroll lebih konsisten.
                            </p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: .6rem;">
                            <a href="{{ route('salary-grade-settings-director') }}" class="btn btn-outline-light btn-sm salary-hero-btn">
                                <i class="fas fa-layer-group mr-1"></i> Atur Tier/Grade
                            </a>
                            <a href="{{ route('data-master-payslip-assessment') }}" class="btn btn-warning btn-sm salary-hero-btn font-weight-600">
                                <i class="fas fa-plus mr-1"></i> Tambah Data Master
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="salary-insight-card">
                            <div class="insight-label">Total Data</div>
                            <div class="insight-value" id="salaryTotalCount">0</div>
                            <div class="insight-note">Master gaji tersimpan</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="salary-insight-card">
                            <div class="insight-label">Status Aktif</div>
                            <div class="insight-value text-success" id="salaryActiveCount">0</div>
                            <div class="insight-note">Dipakai pada payroll</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="salary-insight-card">
                            <div class="insight-label">Status Non Aktif</div>
                            <div class="insight-value text-danger" id="salaryInactiveCount">0</div>
                            <div class="insight-note">Arsip historis</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="salary-insight-card">
                            <div class="insight-label">Update Terakhir</div>
                            <div class="insight-value insight-date" id="salaryLatestUpdate">-</div>
                            <div class="insight-note">Berdasarkan data terbaru</div>
                        </div>
                    </div>
                </div>

                <div class="card salary-table-card border-0">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap: .8rem;">
                        <div>
                            <h4 class="mb-0">Daftar Master Gaji Karyawan</h4>
                            <small class="text-muted">Pantau nominal, tier/grade, dan periode gaji tiap karyawan.</small>
                        </div>
                        <div class="d-flex align-items-center flex-wrap" style="gap: .5rem;">
                            <button class="btn btn-primary btn-sm" id="refreshButton">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                id="salarySearchInput"
                                placeholder="Cari nama karyawan atau grade"
                                style="min-width: 230px;">
                            <select class="form-control form-control-sm" id="salaryStatusFilter" style="min-width: 160px;">
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
                            <button type="button" class="close" onclick="salaryManager.hideSuccess()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div id="errorMessage" class="alert alert-danger alert-dismissible" style="display: none;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span id="errorText"></span>
                            <button type="button" class="close" onclick="salaryManager.hideError()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="table-responsive salary-table-wrapper">
                            <table class="table table-hover align-middle mb-0" id="salaryTable">
                                <thead>
                                    <tr class="overtime-data">
                                        <th class="text-center" style="width: 5%;">No.</th>
                                        <th class="text-center" style="width: 18%;">Nama Karyawan</th>
                                        <th class="text-center" style="width: 15%;">Tier/Grade</th>
                                        <th class="text-center" style="width: 19%;">Nominal Gaji</th>
                                        <th class="text-center" style="width: 15%;">Periode</th>
                                        <th class="text-center" style="width: 10%;">Status</th>
                                        <th class="text-center" style="width: 18%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="salariesTableBody"></tbody>
                            </table>
                        </div>

                        <div id="emptyState" class="text-center py-5" style="display: none;">
                            <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data master gaji yang sesuai filter.</p>
                        </div>

                        <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Memuat data master gaji...</p>
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
                <button class="modal-close" onclick="salaryManager.closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Data Gaji</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus data gaji untuk <strong id="deleteUserName"></strong>?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem" onclick="salaryManager.closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem" onclick="salaryManager.confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSalaryModal" tabindex="-1" role="dialog" aria-labelledby="editSalaryModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header payslip-modal">
                    <h5 class="modal-title" id="editSalaryModalTitle">Edit Data Master Gaji</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editSalaryForm">
                    @csrf

                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_user_id" name="id_user">

                    <div class="modal-body" style="padding: 1rem 2rem;">
                        <h6 style="margin-bottom: 2rem;">Perbarui data master gaji karyawan</h6>

                        <div class="form-group" style="margin-bottom: 0.8rem;">
                            <label>Nama Karyawan</label>
                            <input type="text" class="form-control" id="edit_user_name" readonly>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.8rem;">
                            <label>Tier/Grade</label>
                            <select class="form-control" id="edit_tier_grade" name="tier_grade">
                                <option value="">Pilih tier/grade</option>
                            </select>
                            <div class="invalid-feedback" id="edit_tier_grade_error"></div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.8rem;">
                            <label>Nominal Gaji (Gross)</label>
                            <input type="text" class="form-control rupiah" id="edit_salary_amount" name="salary_amount" required>
                            <div class="invalid-feedback" id="edit_salary_amount_error"></div>
                        </div>

                        <div id="editPph21Preview"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0.8rem; margin-top: 1rem;">
                                    <label>Periode Mulai</label>
                                    <input type="date" class="form-control" id="edit_period_start" name="period_start">
                                    <div class="invalid-feedback" id="edit_period_start_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0.8rem; margin-top: 1rem;">
                                    <label>Periode Selesai</label>
                                    <input type="date" class="form-control" id="edit_period_end" name="period_end">
                                    <div class="invalid-feedback" id="edit_period_end_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0.8rem;">
                            <label>Status</label>
                            <select class="form-control" id="edit_status" name="status" title="Pilih status">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non Aktif</option>
                            </select>
                            <div class="invalid-feedback" id="edit_status_error"></div>
                        </div>
                    </div>
                    <div class="payslip-footer" style="padding: 0px 2rem">
                        <button type="button" class="btn btn-secondary btn-footer" data-dismiss="modal" aria-label="Close">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger btn-footer" id="editSubmitButton">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('customStyle')
    <style>
        .salary-master-page {
            --salary-primary: #0f766e;
            --salary-secondary: #134e4a;
            --salary-accent: #f59e0b;
            --salary-soft: #f0fdfa;
            --salary-border: #d1fae5;
            position: relative;
            font-family: "Poppins", "Segoe UI", sans-serif;
        }

        .salary-master-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 12% 4%, rgba(15, 118, 110, .09), transparent 30%),
                radial-gradient(circle at 88% 22%, rgba(245, 158, 11, .09), transparent 24%);
            pointer-events: none;
        }

        .salary-master-page .section-body {
            position: relative;
            z-index: 1;
        }

        .salary-hero-card {
            background: linear-gradient(140deg, var(--salary-secondary), var(--salary-primary));
            color: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 26px rgba(15, 118, 110, .2);
            animation: salaryFadeUp .45s ease forwards;
        }

        .salary-hero-title {
            font-weight: 700;
            letter-spacing: .2px;
        }

        .salary-hero-subtitle {
            color: rgba(255, 255, 255, .86);
            max-width: 680px;
        }

        .salary-hero-btn {
            border-radius: 999px;
            padding: .45rem .95rem;
        }

        .salary-insight-card {
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--salary-border);
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
            padding: 1rem 1.05rem;
            height: 100%;
            opacity: 0;
            transform: translateY(8px);
            animation: salaryFadeUp .45s ease forwards;
        }

        .salary-master-page .row.mb-2>div:nth-child(1) .salary-insight-card {
            animation-delay: .08s;
        }

        .salary-master-page .row.mb-2>div:nth-child(2) .salary-insight-card {
            animation-delay: .12s;
        }

        .salary-master-page .row.mb-2>div:nth-child(3) .salary-insight-card {
            animation-delay: .16s;
        }

        .salary-master-page .row.mb-2>div:nth-child(4) .salary-insight-card {
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
            font-size: 1.55rem;
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

        .salary-table-card {
            border-radius: 14px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .07);
            opacity: 0;
            transform: translateY(10px);
            animation: salaryFadeUp .5s ease .24s forwards;
        }

        .salary-table-card .card-header {
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 14px 14px 0 0;
        }

        .salary-table-wrapper table thead th {
            background: var(--salary-soft);
            color: #0f172a;
            border-bottom: 1px solid #bae6fd;
        }

        .salary-table-wrapper table tbody tr:hover {
            background: #f8fafc;
        }

        .salary-table-wrapper table tbody tr {
            transition: background-color .2s ease;
        }

        @keyframes salaryFadeUp {
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
            .salary-hero-title {
                font-size: 1.22rem;
            }

            .salary-table-card .card-header {
                align-items: flex-start !important;
            }

            #salarySearchInput,
            #salaryStatusFilter {
                min-width: 100% !important;
            }
        }
    </style>
@endpush

@push('customScript')
    <script src="{{ asset('js/DataMaster/master-salary.js') }}"></script>
@endpush
