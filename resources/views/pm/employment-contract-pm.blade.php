@extends('layouts.app-pm')

@section('title', 'Kontrak Kerja PM')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/pm/employment-contract-pm.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kontrak Kerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Kontrak Kerja</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="section-title-contract">Kontrak Kerja</h2>
                        <p class="section-lead-contract">Lihat Kontrak Kerjamu Disini!</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Kontrak Kerja</h4>
                            </div>

                            <hr class="mt-0 mb-0" style="border-color: #D51C48">

                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered" id="kegiatanTable">
                                        <thead>
                                            <tr>
                                                <th class="text-dark text-center">No.</th>
                                                <th class="text-dark text-center">Nomor Kontrak</th>
                                                <th class="text-dark text-center">Tanggal Kontrak</th>
                                                <th class="text-dark text-center">Status</th>
                                                <th class="text-dark text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-dark text-center">1</td>
                                                <td class="text-dark text-center">0001</td>
                                                <td class="text-dark text-center">1 September 2025</td>
                                                <td class="text-dark text-center"><span
                                                        class="badge badge-success-employment">Selesai</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary"
                                                        style="padding: 0.2rem 0.8rem; border-radius: 20px;"
                                                        data-toggle="modal" data-target="#ModalDetail">
                                                        Rincian
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark text-center">2</td>
                                                <td class="text-dark text-center">0002</td>
                                                <td class="text-dark text-center">1 September 2025</td>
                                                <td class="text-dark text-center"><span
                                                        class="badge badge-warning-employment">Menunggu</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success"
                                                        style="padding: 0.2rem 0.7rem; border-radius: 20px;"
                                                        data-toggle="modal" data-target="#ModalSetujui">
                                                        Setujui
                                                    </button>
                                                    <button type="button" class="btn btn-warning"
                                                        style="padding: 0.2rem 1rem; border-radius: 20px;"
                                                        data-toggle="modal" data-target="#ModalRevisi">
                                                        Revisi
                                                    </button>
                                                    <button type="button" class="btn btn-primary"
                                                        style="padding: 0.2rem 0.8rem; border-radius: 20px;"
                                                        data-toggle="modal" data-target="#ModalDetail">
                                                        Rincian
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark text-center">3</td>
                                                <td class="text-dark text-center">0003</td>
                                                <td class="text-dark text-center">1 September 2025</td>
                                                <td class="text-dark text-center"><span
                                                        class="badge badge-danger-employment">Revisi</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary"
                                                        style="padding: 0.2rem 0.8rem; border-radius: 20px;"
                                                        data-toggle="modal" data-target="#ModalDetail">
                                                        Rincian
                                                    </button>
                                                </td>
                                            </tr>
                                            {{-- <tr>
                                                <td class="text-center">4</td>
                                                <td class="text-center">0004</td>
                                                <td class="text-center">1 September 2025</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#ModalTolak">
                                                        Tolak
                                                    </button>
                                                </td>
                                            </tr> --}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- Modal-->
    <div class="modal fade" id="ModalSetujui" tabindex="-1" role="dialog" aria-labelledby="ModalSetujuiTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 align-items-start">
                    <div class="icon-container">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left px-4 pb-3">
                    <h6 class="modal-title fw-bold mb-3">Setujui Kontrak Kerja</h6>
                    <p class="text-muted small mb-4 compact-text">
                        Apakah anda yakin ingin menyetujui kontrak kerja ini?<br>
                        Tindakan ini tidak bisa dibatalkan.
                    </p>
                    <form id="activityForm">
                        <div class="form-group">
                            <label for="activityName" class="form-label">Alasan Disetujui</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan Alasan Disetujui">
                        </div>
                    </form>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-danger btn-sm flex-fill py-2" style="width: 1.5rem"
                            data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success btn-sm flex-fill py-2 ml-2" style="width: 1.5rem"
                            id="confirmSetujui">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalRevisi" tabindex="-1" role="dialog" aria-labelledby="ModalRevisiTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 align-items-start">
                    <div class="icon-container">
                        <i class="fas fa-edit text-warning"></i>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left px-4 pb-3">
                    <h6 class="modal-title fw-bold mb-3">Revisi Kontrak Kerja</h6>
                    <p class="text-muted small mb-4 compact-text">
                        Apakah anda yakin ingin merevisi kontrak kerja ini?
                    </p>
                    <form id="activityForm">
                        <div class="form-group">
                            <label for="activityName" class="form-label">Alasan Direvisi</label>
                            <input type="text" class="form-control" id="activityName"
                                placeholder="Masukkan Alasan Direvisi">
                        </div>
                    </form>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-danger btn-sm flex-fill py-2" style="width: 1.5rem"
                            data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success btn-sm flex-fill py-2 ml-2" style="width: 1.5rem"
                            id="confirmRevisi">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="ModalDetail" tabindex="-1" role="dialog" aria-labelledby="ModalDetailTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 align-items-start">
                    <div class="icon-container">
                        <i class="fas fa-info-circle text-info"></i>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left px-4 pb-3">
                    <h6 class="modal-title fw-bold mb-3">Detail Kontrak Kerja</h6>
                    <p class="text-muted small mb-4 compact-text">
                        Melihat detail informasi kontrak kerja.
                    </p>
                    <div class="d-flex flex-column gap-2 w-100" style="margin-top: 35px">
                        <a href="{{ route('pdf-employment-contract-pm') }}" type="button" class="btn btn-primary btn-sm flex-fill py-2" style="border-radius: 8px"
                            id="confirmDetail">Lihat
                            Detail</a>
                        <button type="button" class="btn btn-danger btn-sm flex-fill py-2"
                            style="border-radius: 8px" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="ModalTolak" tabindex="-1" role="dialog" aria-labelledby="ModalTolakTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 align-items-start">
                    <div class="icon-container bg-danger">
                        <i class="fas fa-times-circle text-white"></i>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left px-4 pb-3">
                    <h6 class="modal-title fw-bold mb-3">Kontrak kerja Ditolak</h6>
                    <p class="text-muted small mb-4 compact-text">
                        Kontrak kerja ini akan ditolak. Apakah anda yakin ingin menolak kontrak kerja ini?<br>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill py-2"
                            data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary btn-sm flex-fill py-2"
                            id="confirmSetujui">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('scripts')
    <script src="{{ asset('js/page/employment-contract.js') }}"></script>
@endpush
