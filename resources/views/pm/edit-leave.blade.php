@extends('layouts.app-pm')

@section('title', 'Edit Pengajuan Cuti')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Pengajuan Cuti</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('leave-histories-pm') }}">Riwayat Cuti</a></div>
                    <div class="breadcrumb-item">Edit Riwayat Cuti</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Formulir Pengajuan Cuti
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px;">
                            Lengkapi formulir di bawah ini untuk mengajukan izin
                        </div>
                    </div>
                    <div class="sisa-cuti-badge">
                        Sisa Cuti
                        <span class="badge-number">5</span>
                        Hari
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST">
                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Data Pegawai</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-employee">
                                                <label>Nama Karyawan</label>
                                                <input type="text" class="form-control"  value="Anonymous" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-employee">
                                                <label>Jabatan</label>
                                                <input type="text" class="form-control"  value="PM" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-employee">
                                                <label>Unit Kerja</label>
                                                <input type="text" class="form-control"
                                                    value="Mascitra" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Jenis Cuti Yang Diambil</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-employee">
                                        <label style="color: #d51c48; font-weight: bold">Pilih Jenis Cuti Kamu*</label>
                                        <div class="form-group d-flex"
                                            style="padding: 1rem; justify-content: space-between; margin-bottom: 0;">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio1" value="option1" checked>
                                                <label class="form-check-label" for="inlineRadio1"
                                                    style="font-weight: normal;">Cuti Tahunan</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio2" value="option2">
                                                <label class="form-check-label" for="inlineRadio2"
                                                    style="font-weight: normal;">Cuti Sakit</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio3" value="option3">
                                                <label class="form-check-label" for="inlineRadio3"
                                                    style="font-weight: normal;">Cuti Izin Keperluan
                                                    Khusus</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio4" value="option4">
                                                <label class="form-check-label" for="inlineRadio4"
                                                    style="font-weight: normal;">Cuti Tanpa Dibayar</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio5" value="option5">
                                                <label class="form-check-label" for="inlineRadio5"
                                                    style="font-weight: normal;">Cuti Bersalin / Mendampingi
                                                    Persalinan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Alasan Cuti</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-employee">
                                        <label style="font-weight: bold">Alasan*</label>
                                        <textarea class="form-control" name="alasan" style="height: 120px;" >Acara keluarga di Dubai</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Lama Cuti</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="leave-long">
                                            <div class="form-employee">
                                                <label style="font-weight: bold">Selama (Otomatis)</label>
                                                <input type="text" class="form-control" id="totalDays"
                                                    value="2 hari">
                                            </div>
                                            <div class="form-employee">
                                                <label for="tgl" style="font-weight: bold">Tanggal*</label>
                                                <div class="experience-date">
                                                    <input type="text" id="startDate" class="form-control"
                                                        name="start_date" placeholder="Tanggal mulai" value="02-09-2025"
                                                         />
                                                    <input type="text" id="endDate" class="form-control"
                                                        name="end_date" placeholder="Tanggal selesai" value="04-09-2025"
                                                         />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Catatan Cuti (Opsional)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-employee">
                                        <label style="font-weight: bold">Catatan (Opsional)</label>
                                        <textarea class="form-control" name="catatan" style="height: 100px;" ></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Alamat Selama Menjalankan Cuti</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-employee">
                                                <label style="font-weight: bold">Alamat*</label>
                                                <textarea class="form-control" name="alamat_cuti" style="height: 100px;" >Burj Khalifa, Dubai</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-employee">
                                                <label style="font-weight: bold">Telepon*</label>
                                                <input type="telp" class="form-control" value="08123456789" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="leave-button">
                                <a href="{{ route('leave-histories-pm') }}" class="btn btn-light">Kembali</a>
                                <a href="{{ route('leave-histories-pm') }}" class="btn btn-danger">Simpan Perubahan</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('customScript')
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
@endpush
