@extends('layouts.app')

@section('title', 'Edit Profil')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Profil</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('profile') }}">Profil</a></div>
                    <div class="breadcrumb-item">Edit Profil</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px; margin-bottom: 2rem;">
                        <div class="title-lead">
                            Edit Profilku
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px">
                            Edit Data Pribadi Kamu
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card-profile">
                            <div class="top-element">
                                <h4>Profil Saya</h4>
                            </div>
                            <div class="profile-picture">
                                <div class="pfp-img">
                                    <img src="{{ asset('images/profile-picture.jpg') }}" alt="profile">
                                </div>
                                <div class="con-edit-icon">
                                    <label for="profileInput" class="edit-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                        </svg>
                                    </label>
                                    <input type="file" id="profileInput" name="profile_picture" style="display: none">
                                </div>
                                <div class="profile-name">
                                    <h1>Anonymous</h1>
                                    <p>anonym@gmail.com</p>
                                </div>
                            </div>
                            <div class="button-edit">
                                <div class="back-profile" style="margin-bottom: 2rem;">
                                    <a href="{{ route('profile') }}">Kembali</a>
                                </div>
                                <div class="edit-profile" style="margin-bottom: 2rem;">
                                    <a href="{{ route('profile') }}">Simpan Perubahan</a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Data Karyawan</h4>
                            </div>
                            <div class="card-body">
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>NIP*</label>
                                        <input type="text" class="form-control" value="123456789">
                                    </div>
                                    <div class="form-employee">
                                        <label>Nama Karyawan*</label>
                                        <input type="text" class="form-control" value="Anonymous">
                                    </div>
                                    <div class="form-employee">
                                        <label>Email*</label>
                                        <input type="text" class="form-control" value="anonymous@gmail.com">
                                    </div>
                                    <div class="form-employee">
                                        <label>Jenis Karyawan*</label>
                                        <input type="text" class="form-control" value="Freelance">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Form Data Karyawan</h4>
                            </div>
                            <div class="card-body">
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Jenis Kelamin*</label>
                                        <input type="text" class="form-control" value="Perempuan">
                                    </div>
                                    <div class="form-employee">
                                        <label>Tempat Lahir*</label>
                                        <input type="text" class="form-control" value="Jember">
                                    </div>
                                    <div class="form-employee">
                                        <label>Tanggal Lahir*</label>
                                        <input type="date" class="form-control" value="1999-09-27">
                                    </div>
                                    <div class="form-employee">
                                        <label>NIK*</label>
                                        <input type="text" class="form-control" value="3510012345678004">
                                    </div>
                                </div>
                                <div class="employee-data-wrapper">
                                    <div class="employee-data" style="width: 26%;">
                                        <div class="form-employee employee-address" style="padding: 0;">
                                            <label>Alamat*</label>
                                            <textarea name="address" id="address" class="form-control">Jember</textarea>
                                        </div>
                                    </div>
                                    <div class="second-wrapper" style="width: 74%;">
                                        <div class="employee-data" style="padding: 1rem 1rem 1rem 0;">
                                            <div class="form-employee">
                                                <label>Pilih Kecamatan*</label>
                                                <input type="text" class="form-control" value="Sumbersari">
                                            </div>
                                            <div class="form-employee">
                                                <label>Pilih Kabupaten*</label>
                                                <input type="text" class="form-control" value="Jember">
                                            </div>
                                            <div class="form-employee">
                                                <label>Pilih Provinsi*</label>
                                                <input type="text" class="form-control" value="Jember">
                                            </div>
                                        </div>
                                        <div class="employee-data" style="padding: 1rem 1rem 1rem 0;">
                                            <div class="form-employee">
                                                <label>No Telepon*</label>
                                                <input type="text" class="form-control" value="08123456789">
                                            </div>
                                            <div class="form-employee">
                                                <label>Foto*</label>
                                                <input type="file" class="form-control">
                                            </div>
                                            <div class="form-employee">
                                                <input type="text" class="form-control" value="Perempuan" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Pendidikan Terakhir</h4>
                            </div>
                            <div class="card-body">
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Jurusan*</label>
                                        <input type="text" class="form-control" value="Teknologi Informasi">
                                    </div>
                                    <div class="form-employee">
                                        <label>Instansi*</label>
                                        <input type="text" class="form-control" value="Universitas Jember">
                                    </div>
                                    <div class="form-employee">
                                        <label>Tahun Lulus*</label>
                                        <input type="text" class="form-control" value="2025">
                                    </div>
                                    <div class="form-employee">
                                        <label>File Ijazah*</label>
                                        <input type="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div id="experiences-container">
                                <div class="card-header-leave">
                                    <h4>Pengalaman</h4>
                                </div>
                                <div class="d-flex"
                                    style="flex-direction: row; padding: 2rem 2.5rem 2rem 1rem; justify-content: right;">
                                    <button class="btn btn-primary btn-custom" onclick="addExperience()">Tambah
                                        Pengalaman</button>
                                </div>
                                <div class="experience-item">
                                    <div class="card-body">
                                        <div class="employee-data">
                                            <div class="form-employee">
                                                <label>Bidang*</label>
                                                <input type="text" class="form-control" value="UI/UX Designer">
                                            </div>
                                            <div class="form-employee">
                                                <label>Nama Perusahaan*</label>
                                                <input type="text" class="form-control" value="Mascitra">
                                            </div>
                                            <div class="form-employee">
                                                <label>Tanggal*</label>
                                                <div class="experience-date">
                                                    <input type="date" class="form-control" value="2025-09-01">
                                                    <input type="date" class="form-control" value="2025-09-01">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" style="flex-direction: row; padding: 1rem; gap: 2rem;">
                                            <button class="btn btn-danger btn-custom"
                                                onclick="removeExperience(this)">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div id="document-container">
                                <div class="card-header-leave">
                                    <h4>Kelengkapan Dokumen*</h4>
                                </div>
                                <div class="d-flex"
                                    style="flex-direction: row; padding: 2rem 2.5rem 2rem 1rem; justify-content: right;">
                                    <button class="btn btn-primary btn-custom" onclick="addDocument()">Tambah
                                        Dokumen</button>
                                </div>
                                <div class="document-item">
                                    <div class="card-body">
                                        <div class="employee-data">
                                            <div class="form-employee">
                                                <label>Nama Dokumen*</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-employee">
                                                <label>Jenis Dokumen*</label>
                                                <select class="select2 form-control">
                                                    <option value="KTP">KTP</option>
                                                    <option value="NIK">SIM</option>
                                                    <option value="Ijazah">Ijazah</option>
                                                </select>
                                            </div>
                                            <div class="form-employee">
                                                <label>Upload*</label>
                                                <input type="file" class="form-control">
                                            </div>
                                        </div>
                                        <div class="d-flex" style="flex-direction: row; padding: 1rem; gap: 2rem;">
                                            <button class="btn btn-danger btn-custom"
                                                onclick="removeDocument(this)">Hapus</button>
                                        </div>
                                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function addExperience() {
            const container = document.getElementById('experiences-container');
            const newExperience = document.createElement('div');
            newExperience.className = 'experience-item';
            newExperience.innerHTML = `
                                    <div class="card-body">
                                        <div class="employee-data">
                                            <div class="form-employee">
                                                <label>Bidang*</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-employee">
                                                <label>Nama Perusahaan*</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-employee">
                                                <label>Tanggal*</label>
                                                <div class="experience-date">
                                                    <input type="date" class="form-control">
                                                    <input type="date" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" style="flex-direction: row; padding: 1rem; gap: 2rem;">
                                            <button class="btn btn-danger btn-custom" onclick="removeExperience(this)">Hapus</button>
                                        </div>
                                    </div>
            `;
            container.appendChild(newExperience);
        }

        function removeExperience(button) {
            const experienceItem = button.closest('.experience-item');
            if (experienceItem) {
                experienceItem.remove();
            }
        }

        function addDocument() {
            const container = document.getElementById('document-container');
            const newDocument = document.createElement('div');
            newDocument.className = 'document-item';
            newDocument.innerHTML = `
                                    <div class="card-body">
                                        <div class="employee-data">
                                            <div class="form-employee">
                                                <label>Nama Dokumen*</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-employee">
                                                <label>Jenis Dokumen*</label>
                                                <select class="select2 form-control">
                                                    <option value="KTP">KTP</option>
                                                    <option value="NIK">SIM</option>
                                                    <option value="Ijazah">Ijazah</option>
                                                </select>
                                            </div>
                                            <div class="form-employee">
                                                <label>Upload*</label>
                                                <input type="file" class="form-control">
                                            </div>
                                        </div>
                                        <div class="d-flex" style="flex-direction: row; padding: 1rem; gap: 2rem;">
                                            <button class="btn btn-danger btn-custom"
                                                onclick="removeDocument(this)">Hapus</button>
                                        </div>
                                    </div>
            `;
            container.appendChild(newDocument);
            $(newDocument).find('.select2').select2();
        }

        function removeDocument(button) {
            const documentItem = button.closest('.document-item');
            if (documentItem) {
                documentItem.remove();
            }
        }

        $(document).ready(function() {
            $('.select2').select2();

            $(document).on('DOMNodeInserted', function(e) {
                if ($(e.target).find('.select2').length) {
                    $(e.target).find('.select2').select2();
                }
            });
        });
    </script>
@endpush
