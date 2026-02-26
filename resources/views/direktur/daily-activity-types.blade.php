@extends('layouts.app-director')

@section('title', 'Jenis Kegiatan Harian')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/director/daily-activity-types.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Jenis Kegiatan Harian</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Absensi</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="title-type-daily">List Jenis Kegiatan Harian</h2>
                        <p class="lead-type-daily">List jenis kegiatan</p>
                    </div>
                    <div class="col-md-6 text-md-right text-center mb-4">
                        <div class="d-inline-flex"
                            style="gap: 12px;">
                            <button class="btn btn-filter px-3 mr-3"
                                data-toggle="modal" data-target="#addActivityModal" style="width: 60px; height: 60px;">
                                <i class="fas fa-plus"style="font-size: 1.4rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Jenis Kegiatan Harian</h4>
                        </div>
                        <hr class="mt-0 mb-0" style="border-color: #D51C48">
                        <div class="card-body">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="kegiatanTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Nama Jenis Kegiatan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">Workshop karyawan</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary text-dark" data-toggle="modal"
                                                    data-target="#editActivityModal" data-activity-id="1"
                                                    data-activity-name="Workshop karyawan" style=" width: 5rem">
                                                    {{-- <i class="fas fa-edit"></i>  --}} Edit
                                                </button>
                                                <button type="button" class="btn btn-filter" style=" width: 5rem"
                                                    data-toggle="modal" data-target="#ModalHapus">
                                                    {{-- <i class="fas fa-trash"></i>--}} Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-center">Gathering anak magang</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary text-dark" data-toggle="modal"
                                                    data-target="#editActivityModal" data-activity-id="2"
                                                    data-activity-name="Gathering anak magang" style=" width: 5rem">
                                                    {{-- <i class="fas fa-edit"></i>--}}Edit
                                                </button>
                                                <button type="button" class="btn btn-filter" style=" width: 5rem"
                                                    data-toggle="modal" data-target="#ModalHapus">
                                                    {{-- <i class="fas fa-trash"></i>--}} Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td class="text-center">Kunjungan kepada sekolah SMA Pasirian</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary text-dark" data-toggle="modal"
                                                    data-target="#editActivityModal" data-activity-id="3"
                                                    data-activity-name="Kunjungan kepada sekolah SMA Pasirian"
                                                    style=" width: 5rem">
                                                    {{-- <i class="fas fa-edit"></i>--}} Edit
                                                </button>
                                                <button type="button" class="btn btn-filter" style=" width: 5rem"
                                                    data-toggle="modal" data-target="#ModalHapus">
                                                    {{-- <i class="fas fa-trash"></i>--}} Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td class="text-center">Menghadiri presentasi akhir anak magang</td>
                                            <td class="text-center">
                                                <button class="btn btn-secondary text-dark" data-toggle="modal"
                                                    data-target="#editActivityModal" data-activity-id="4"
                                                    data-activity-name="Menghadiri presentasi akhir anak magang"
                                                    style=" width: 5rem">
                                                    {{-- <i class="fas fa-edit"></i> --}} Edit
                                                </button>
                                                <button type="button" class="btn btn-filter" style=" width: 5rem"
                                                    data-toggle="modal" data-target="#ModalHapus">
                                                    {{-- <i class="fas fa-trash"></i>--}} Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <div class="modal fade" id="addActivityModal" tabindex="-1" role="dialog" aria-labelledby="addActivityModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addActivityModalTitle">Tambahan Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-description">
                        <p class="description-text">Lengkapi form berikut dengan benar!</p>
                    </div>
                    <form id="activityForm">
                        <div class="form-group">
                            <label for="activityName" class="form-label">Nama Jenis Kegiatan</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan nama jenis kegiatan">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        style="width: 5.5rem"><i class="fas fa-xmark"></i> Batal</button>
                    <button type="button" class="btn btn-primary" style="width: 5.5rem"><i class="fas fa-plus"></i> Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog"
        aria-labelledby="editActivityModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editActivityModalTitle">Edit Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-description">
                        <p class="description-text">Lengkapi form berikut dengan benar!</p>
                    </div>
                    <form id="activityForm">
                        <div class="form-group">
                            <label for="activityName" class="form-label">Nama Jenis Kegiatan</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan nama jenis kegiatan">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        style="width: 5rem">Batal</button>
                    <button type="button" class="btn btn-secondary text-dark" style="width: 5rem">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="ModalHapusTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-container">
                        <i class="fa-solid fa-trash text-danger"></i>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left px-4 pb-3">
                    <h5 class="modal-title" id="exampleModalLongTitle">Hapus Jenis Kegiatan</h5>
                    <div>
                        Yakin ingin menghapus Jenis Kegiatan ini? Tindakan ini tidak dapat dibatalkan.
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        style="width: 5.5rem">Batal</button>
                    <button type="button" class="btn btn-primary" style="width: 5.5rem">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/direktur/daily-activity-types.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#editActivityModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Tombol yang memicu modal
                var activityName = button.data('activity-name'); // Extract info dari data-* attributes
                var activityId = button.data('activity-id');

                var modal = $(this);
                modal.find('#activityName').val(activityName);
                // Jika perlu simpan ID untuk proses update
                modal.find('#activityForm').data('activity-id', activityId);
            });
        });
    </script>
@endpush
