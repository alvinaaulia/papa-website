@extends('layouts.app')

@section('title', 'Kontrak Kerja')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employment-contract.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kontrak Kerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-employee') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kontrak Kerja</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tabel Kontrak Kerja</h4>
                            <div class="card-header-form">
                                <form>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <div class="input-group-btn">
                                            <button class="btn btn-filter"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <hr class="mt-0 mb-0" style="border-color: #D51C48">

                        <div class="card-body">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="kegiatanTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Nomor Kontrak</th>
                                            <th class="text-center">Tanggal Kontrak</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">0001</td>
                                            <td class="text-center">1 September 2025</td>
                                            <td class="text-center"><span
                                                    class="badge badge-success-employment">Disetujui</span>
                                            </td>
                                            <td class="text-center">
                                                {{-- <button type="button" class="btn btn-success"
                                                    style="padding: 0.2rem 0.7rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalSetujui">
                                                    Setujui
                                                </button> --}}
                                                <button type="button" class="btn btn-primary"
                                                    style="padding: 0.2rem 0.8rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalDetail">
                                                    Rincian
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-center">0002</td>
                                            <td class="text-center">1 September 2025</td>
                                            <td class="text-center"><span
                                                    class="badge badge-primary-employment">Menunggu</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-success"
                                                    style="padding: 0.2rem 0.7rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalSetujui">
                                                    Setujui
                                                </button>
                                                <button type="button" class="btn btn-warning"
                                                    style="padding: 0.2rem 1rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalRevisi">
                                                    Revisi
                                                </button>
                                                <button type="button" class="btn btn-primary"
                                                    style="padding: 0.2rem 0.8rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalDetail">
                                                    Rincian
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td class="text-center">0003</td>
                                            <td class="text-center">1 September 2025</td>
                                            <td class="text-center"><span
                                                    class="badge badge-danger-employment">Revisi</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary"
                                                    style="padding: 0.2rem 0.8rem; border-radius: 20px;" data-toggle="modal"
                                                    data-target="#ModalDetail">
                                                    Rincian
                                                </button>
                                            </td>
                                        </tr>
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
                    <div class="d-flex gap-3 w-100">
                        <button type="button" class="btn btn-danger btn-sm flex-fill py-2 " style="width: 1.5rem"
                            data-dismiss="modal">Batal</button>
                        <a href="{{ route('pdf-employment-contract-employee') }}" type="button"
                            class="btn btn-primary btn-sm flex-fill py-2" style="width: 1.5rem"
                            id="confirmDetail">Lihat
                            Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/page/employment-contract.js') }}"></script>
@endpush
