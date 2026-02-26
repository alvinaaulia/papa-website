@extends('layouts.app-hrd')

@section('title', 'Edit Data Karyawan')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('main')

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    </head>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Data Karyawan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('data-employees-hrd') }}">Data Karyawan</a></div>
                    <div class="breadcrumb-item">Edit Data Karyawan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Edit Data Karyawan
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                            Daftar Data Karyawan
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST">
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
                            <div class="overtime-button">
                                <a href="{{ route('data-employees-hrd') }}" class="btn btn-light">Batal</a>
                                <a href="{{ route('data-employees-hrd') }}" class="btn btn-danger">Simpan Perubahan</a>
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
