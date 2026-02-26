@extends('layouts.app')

@section('title', 'Profil')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Profil</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px; margin-bottom: 2rem;">
                        <div class="title-lead">
                            Profilku
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px">
                            Data Pribadi Kamu
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
                                <div class="profile-name">
                                    <h1>Anonymous</h1>
                                    <p>anonym@gmail.com</p>
                                </div>
                            </div>
                            <div class="button-edit">
                                <div class="edit-profile" style="margin-bottom: 2rem;">
                                    <a href="{{ route('edit-profile') }}"> Edit Profil </a>
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
                                        <label>NIP</label>
                                        <input type="text" class="form-control" value="123456789" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Nama Karyawan</label>
                                        <input type="text" class="form-control" value="Anonymous" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Email</label>
                                        <input type="text" class="form-control" value="anonymous@gmail.com" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Jenis Karyawan</label>
                                        <input type="text" class="form-control" value="Freelance" readonly>
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
                                        <label>Jenis Kelamin</label>
                                        <input type="text" class="form-control" value="Perempuan" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" value="Jember" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" value="1999-09-27" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="3510012345678004" readonly>
                                    </div>
                                </div>
                                <div class="employee-data-wrapper">
                                    <div class="employee-data" style="width: 26%;">
                                        <div class="form-employee employee-address" style="padding: 0;">
                                            <label>Alamat</label>
                                            <textarea name="address" id="address" class="form-control" readonly>Jember</textarea>
                                        </div>
                                    </div>
                                    <div class="second-wrapper" style="width: 74%;">
                                        <div class="employee-data" style="padding: 1rem 1rem 1rem 0;">
                                            <div class="form-employee">
                                                <label>Pilih Kecamatan</label>
                                                <input type="text" class="form-control" value="Sumbersari" readonly>
                                            </div>
                                            <div class="form-employee">
                                                <label>Pilih Kabupaten</label>
                                                <input type="text" class="form-control" value="Jember" readonly>
                                            </div>
                                            <div class="form-employee">
                                                <label>Pilih Provinsi</label>
                                                <input type="text" class="form-control" value="Jember" readonly>
                                            </div>
                                        </div>
                                        <div class="employee-data" style="padding: 1rem 1rem 1rem 0;">
                                            <div class="form-employee">
                                                <label>No Telepon</label>
                                                <input type="text" class="form-control" value="08123456789" readonly>
                                            </div>
                                            <div class="form-employee">
                                                <label>Foto</label>
                                                <input type="file" class="form-control" readonly>
                                            </div>
                                            <div class="form-employee">
                                                <input type="text" class="form-control" value="Perempuan" readonly
                                                    hidden>
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
                                        <label>Jurusan</label>
                                        <input type="text" class="form-control" value="Teknologi Informasi"
                                            readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Instansi</label>
                                        <input type="text" class="form-control" value="Universitas Jember" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Tahun Lulus</label>
                                        <input type="text" class="form-control" value="2025" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>File Ijazah</label>
                                        <input type="file" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Pengalaman</h4>
                            </div>
                            <div class="card-body">
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Bidang</label>
                                        <input type="text" class="form-control" value="UI/UX Designer" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Nama Perusahaan</label>
                                        <input type="text" class="form-control" value="Mascitra" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Tanggal</label>
                                        <div class="experience-date">
                                            <input type="date" class="form-control" value="2025-09-01" readonly>
                                            <input type="date" class="form-control" value="2025-09-01" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Bidang</label>
                                        <input type="text" class="form-control" value="Frontend Developer" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Nama Perusahaan</label>
                                        <input type="text" class="form-control" value="Mascitra" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Tanggal</label>
                                        <div class="experience-date">
                                            <input type="date" class="form-control" value="2024-09-01" readonly>
                                            <input type="date" class="form-control" value="2024-09-01" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header-leave">
                                <h4>Kelengkapan Dokumen</h4>
                            </div>
                            <div class="card-body">
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Nama Dokumen</label>
                                        <input type="text" class="form-control" value="Ijazah SMK" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Jenis Dokumen</label>
                                        <input type="text" class="form-control" value="Ijazah" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>File</label>
                                        <input type="file" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="employee-data">
                                    <div class="form-employee">
                                        <label>Nama Dokumen</label>
                                        <input type="text" class="form-control" value="KTP" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>Jenis Dokumen</label>
                                        <input type="text" class="form-control" value="KTP" readonly>
                                    </div>
                                    <div class="form-employee">
                                        <label>File</label>
                                        <input type="file" class="form-control" readonly>
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
