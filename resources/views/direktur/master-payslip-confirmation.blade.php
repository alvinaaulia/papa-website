@extends('layouts.app-director')

@section('title', 'Konfirmasi Penentuan Gaji')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Konfirmasi Penentuan Gaji</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('data-master-payslip') }}">Data Master Gaji</a></div>
                    <div class="breadcrumb-item">Konfirmasi</div>
                </div>
            </div>

            <div class="section-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: .5rem;">
                    <div>
                        <h2 class="section-title mb-1">Ringkasan Hasil Penilaian</h2>
                        <p class="section-lead mb-0">Pastikan grade, nominal, dan periode gaji sebelum disimpan.</p>
                    </div>
                    <a href="{{ route('data-master-payslip-assessment') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali ke Penilaian
                    </a>
                </div>

                <div id="confirmationError" class="alert alert-danger" style="display: none;"></div>
                <div id="confirmationSuccess" class="alert alert-success" style="display: none;"></div>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h4>Hasil Grade Penilaian</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-3">
                                    <tr>
                                        <td style="width: 35%;">Karyawan</td>
                                        <td style="width: 5%;">:</td>
                                        <td id="summaryEmployee">-</td>
                                    </tr>
                                    <tr>
                                        <td>Posisi/Jabatan Dinilai</td>
                                        <td>:</td>
                                        <td id="summaryPositionApplied">-</td>
                                    </tr>
                                    <tr>
                                        <td>Total Skor</td>
                                        <td>:</td>
                                        <td id="summaryScore">-</td>
                                    </tr>
                                    <tr>
                                        <td>Skor Kecocokan CV</td>
                                        <td>:</td>
                                        <td id="summaryBaseScore">-</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus Pendidikan</td>
                                        <td>:</td>
                                        <td id="summaryEducationBonus">-</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus Pengalaman</td>
                                        <td>:</td>
                                        <td id="summaryExperienceBonus">-</td>
                                    </tr>
                                    <tr>
                                        <td>Tier/Grade</td>
                                        <td>:</td>
                                        <td id="summaryGrade">-</td>
                                    </tr>
                                    <tr>
                                        <td>Gaji Dasar Grade</td>
                                        <td>:</td>
                                        <td id="summaryBaseSalary">-</td>
                                    </tr>
                                </table>

                                <h6 class="mb-2">Detail Penilaian Kecocokan</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Kriteria</th>
                                                <th class="text-center" style="width: 20%;">Nilai</th>
                                                <th class="text-center" style="width: 20%;">Bobot</th>
                                            </tr>
                                        </thead>
                                        <tbody id="criteriaSummaryBody"></tbody>
                                    </table>
                                </div>

                                <h6 class="mt-3 mb-2">Ringkasan CV</h6>
                                <table class="table table-sm table-borderless mb-2">
                                    <tr>
                                        <td style="width: 35%;">Pendidikan Terakhir</td>
                                        <td style="width: 5%;">:</td>
                                        <td id="summaryEducationLevel">-</td>
                                    </tr>
                                    <tr>
                                        <td>Lama Pengalaman Kerja</td>
                                        <td>:</td>
                                        <td id="summaryYearsExperience">-</td>
                                    </tr>
                                </table>

                                <div class="mb-2">
                                    <small class="text-muted">Kemampuan Relevan</small>
                                    <div id="summaryRelevantSkills" class="mt-1">-</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Riwayat Pendidikan</small>
                                    <div id="summaryEducationHistory" class="mt-1">-</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Riwayat Pekerjaan</small>
                                    <div id="summaryWorkHistory" class="mt-1">-</div>
                                </div>

                                <div class="mt-2" id="summaryNoteWrapper" style="display: none;">
                                    <small class="text-muted">Catatan Penilaian</small>
                                    <div id="summaryNote" class="mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h4>Finalisasi Master Gaji</h4>
                            </div>
                            <div class="card-body">
                                <form id="salaryConfirmationForm">
                                    <div class="form-group">
                                        <label for="id_user">Karyawan</label>
                                        <select class="form-control" id="id_user" name="id_user" required>
                                            <option value="">Pilih karyawan</option>
                                        </select>
                                        <div class="invalid-feedback" id="id_user_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="salary_amount">Nominal Gaji (Gross)</label>
                                        <input type="text" class="form-control" id="salary_amount" name="salary_amount" required>
                                        <div class="invalid-feedback" id="salary_amount_error"></div>
                                    </div>

                                    <div id="confirmationPph21Preview"></div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="period_start">Periode Mulai</label>
                                                <input type="date" class="form-control" id="period_start" name="period_start" required>
                                                <div class="invalid-feedback" id="period_start_error"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="period_end">Periode Selesai</label>
                                                <input type="date" class="form-control" id="period_end" name="period_end" required>
                                                <div class="invalid-feedback" id="period_end_error"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status Master Gaji</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Non Aktif</option>
                                        </select>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-danger" id="saveMasterSalaryButton">
                                            Simpan Data Master Gaji
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('customScript')
    <script src="{{ asset('js/DataMaster/master-salary-confirmation.js') }}"></script>
@endpush
