@extends('layouts.app-pm')

@section('title', 'Rincian Persetujuan Cuti')

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Rincian Persetujuan Cuti</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('leave-approval-pm') }}">Persetujuan Cuti</a></div>
                    <div class="breadcrumb-item">Rincian Persetujuan Cuti</div>
                </div>
            </div>

            <div class="section-body">
                <div class="sub-head-leave">
                    <div class="title-leave">
                        <div class="title-lead">
                            Rincian Pengajuan Cuti
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px;">
                            Rincian Pengajuan Cuti Anda
                        </div>
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
                                    <div class="col">
                                        <div class="col">
                                            <div class="form-group approval-details" style="margin-bottom: 25px;">
                                                <label>Nama Karyawan</label>
                                                <input type="text" class="form-control" readonly value="Anonymous">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group approval-details" style="margin-bottom: 25px;">
                                                <label>Jabatan</label>
                                                <input type="text" class="form-control" readonly value="Karyawan">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group approval-details" style="margin-bottom: 25px;">
                                                <label>Unit Kerja</label>
                                                <input type="text" class="form-control" readonly value="Mascitra">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <textarea class="form-control" name="alasan" style="height: 120px;" readonly>Acara keluarga di Dubai</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header-leave">
                                    <h4>Lama Cuti</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="approval-details" style="margin-bottom: 25px;">
                                                <label>Selama</label>
                                                <input type="text" class="form-control" readonly value="2">
                                            </div>
                                            <div class="approval-details">
                                                <label for="tgl-mulai">Pilih Tanggal</label>
                                                <div class="form-group" style="margin-left: -1.8rem">
                                                    <div class="d-flex" style="flex-direction: row; gap: 2rem;"> 
                                                        <input type="date" id="tgl-mulai" name="tanggal_mulai"
                                                            value="2025-09-04" class="form-control" readonly>
                                                        <div class="d-flex" style="align-items: center"><i class="fa fa-minus" aria-hidden="true"></i></div>
                                                        <input type="date" id="tgl-selesai" name="tanggal_selesai"
                                                            value="2025-09-04" class="form-control" readonly>
                                                    </div>
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
                                    <div class="form-group">
                                        <textarea class="form-control" name="catatan" style="height: 100px;" readonly>Tidak ada catatan.</textarea>
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
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <textarea class="form-control" name="alamat_cuti" style="height: 100px;" readonly>Burj Khalifa, Dubai</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Telepon</label>
                                                <input type="tel" class="form-control" readonly value="08123456789">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="leave-button">
                                <a href="{{ route('leave-approval-pm') }}" class="btn btn-light">Kembali</a>
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
