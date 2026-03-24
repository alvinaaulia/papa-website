@extends('layouts.app-director')

@section('title', 'Form Penilaian Gaji')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Form Penentuan Gaji Berbasis CV</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('data-master-payslip') }}">Data Master Gaji</a></div>
                    <div class="breadcrumb-item">Form Penilaian</div>
                </div>
            </div>

            <div class="section-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: .5rem;">
                    <div>
                        <h2 class="section-title mb-1">Evaluasi CV Kandidat/Karyawan</h2>
                        <p class="section-lead mb-0">
                            Penentuan tier/grade gaji berdasarkan kecocokan CV terhadap jabatan/posisi yang dilamar.
                        </p>
                    </div>
                    <a href="{{ route('data-master-payslip') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div id="assessmentError" class="alert alert-danger" style="display: none;"></div>
                <div id="assessmentInfo" class="alert alert-info" style="display: none;"></div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Penilaian CV</h4>
                            </div>
                            <div class="card-body">
                                <form id="salaryAssessmentForm">
                                    <div class="form-group">
                                        <label for="id_user">Karyawan</label>
                                        <select class="form-control" id="id_user" name="id_user" required>
                                            <option value="">Pilih karyawan</option>
                                        </select>
                                        <div class="invalid-feedback" id="id_user_error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="position_applied">Jabatan/Posisi yang Dilamar</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="position_applied"
                                            name="position_applied"
                                            placeholder="Contoh: Backend Developer"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="education_level">Pendidikan Terakhir</label>
                                        <select class="form-control" id="education_level" name="education_level" required>
                                            <option value="">Pilih pendidikan terakhir</option>
                                            <option value="SMA/SMK">SMA/SMK</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="years_experience">Lama Pengalaman Kerja (tahun)</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="years_experience"
                                            name="years_experience"
                                            min="0"
                                            max="40"
                                            step="1"
                                            placeholder="Contoh: 3"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="relevant_skills">Kemampuan yang Relevan dengan Posisi</label>
                                        <textarea
                                            class="form-control"
                                            id="relevant_skills"
                                            name="relevant_skills"
                                            rows="2"
                                            placeholder="Contoh: Laravel, SQL, REST API, Git"
                                            required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="education_history">Ringkasan Riwayat Pendidikan</label>
                                        <textarea
                                            class="form-control"
                                            id="education_history"
                                            name="education_history"
                                            rows="2"
                                            placeholder="Contoh: S1 Informatika, Universitas X, lulus 2022"
                                            required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="work_history">Ringkasan Riwayat Pekerjaan</label>
                                        <textarea
                                            class="form-control"
                                            id="work_history"
                                            name="work_history"
                                            rows="3"
                                            placeholder="Contoh: 2 tahun Backend Developer di PT ABC, 1 tahun Fullstack di PT XYZ"
                                            required></textarea>
                                    </div>

                                    <hr>
                                    <h6 class="mb-3">Skor Kecocokan CV terhadap Posisi (1 - 5)</h6>

                                    <div class="form-group">
                                        <label for="score_skill_relevance">Relevansi Skill dengan Posisi</label>
                                        <select class="form-control score-input" id="score_skill_relevance" name="score_skill_relevance" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Sangat Tidak Relevan</option>
                                            <option value="2">2 - Kurang Relevan</option>
                                            <option value="3">3 - Cukup Relevan</option>
                                            <option value="4">4 - Relevan</option>
                                            <option value="5">5 - Sangat Relevan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="score_experience_relevance">Relevansi Pengalaman Kerja dengan Posisi</label>
                                        <select class="form-control score-input" id="score_experience_relevance" name="score_experience_relevance" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Sangat Tidak Relevan</option>
                                            <option value="2">2 - Kurang Relevan</option>
                                            <option value="3">3 - Cukup Relevan</option>
                                            <option value="4">4 - Relevan</option>
                                            <option value="5">5 - Sangat Relevan</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="score_education_relevance">Kecocokan Pendidikan dengan Posisi</label>
                                        <select class="form-control score-input" id="score_education_relevance" name="score_education_relevance" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Sangat Tidak Cocok</option>
                                            <option value="2">2 - Kurang Cocok</option>
                                            <option value="3">3 - Cukup Cocok</option>
                                            <option value="4">4 - Cocok</option>
                                            <option value="5">5 - Sangat Cocok</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="score_work_history_quality">Kualitas Riwayat Pekerjaan</label>
                                        <select class="form-control score-input" id="score_work_history_quality" name="score_work_history_quality" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Sangat Kurang</option>
                                            <option value="2">2 - Kurang</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="4">4 - Baik</option>
                                            <option value="5">5 - Sangat Baik</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="score_certification">Sertifikasi/Pelatihan Pendukung</label>
                                        <select class="form-control score-input" id="score_certification" name="score_certification" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Tidak Ada</option>
                                            <option value="2">2 - Sangat Minim</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="4">4 - Baik</option>
                                            <option value="5">5 - Sangat Lengkap</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="score_portfolio">Kualitas Portofolio/Project Relevan</label>
                                        <select class="form-control score-input" id="score_portfolio" name="score_portfolio" required>
                                            <option value="">Pilih nilai</option>
                                            <option value="1">1 - Sangat Kurang</option>
                                            <option value="2">2 - Kurang</option>
                                            <option value="3">3 - Cukup</option>
                                            <option value="4">4 - Baik</option>
                                            <option value="5">5 - Sangat Baik</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="assessment_note">Catatan Penilaian Tambahan (Opsional)</label>
                                        <textarea class="form-control" id="assessment_note" name="assessment_note" rows="3" placeholder="Tambahkan catatan khusus hasil evaluasi CV"></textarea>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-danger" id="assessmentSubmitButton">
                                            Lanjut ke Konfirmasi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Hasil Sementara</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted">Total Skor (0-100)</small>
                                    <div class="h4 mb-0" id="previewScore">-</div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Tier/Grade Rekomendasi</small>
                                    <div class="h5 mb-0" id="previewGrade">-</div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Gaji Dasar Grade</small>
                                    <div class="h5 mb-0" id="previewSalary">-</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Detail Skor</small>
                                    <div id="previewBreakdown" style="font-size: .9rem;">-</div>
                                </div>
                                <div class="alert alert-light mb-0" style="font-size: .9rem;">
                                    Komponen skor: kecocokan skill/pengalaman, kualitas riwayat, sertifikasi/portofolio,
                                    bonus pendidikan, dan bonus lama pengalaman.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('customScript')
    <script src="{{ asset('js/DataMaster/master-salary-assessment.js') }}"></script>
@endpush
