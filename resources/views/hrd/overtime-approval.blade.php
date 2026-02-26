@extends('layouts.app-hrd')

@section('title', 'Persetujuan Lembur')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Persetujuan Lembur</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Persetujuan Lembur</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="head-approval">
                        <div class="title-overtime-approval">
                            <div class="title-lead">
                                Persetujuan Lembur
                            </div>
                            <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                                Daftar Persetujuan Lembur Karyawan
                            </div>
                        </div>
                        <div class="approval-filter">
                            <div class="app-filter" onclick="openFilterModal()">
                                <div class="filter-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                                        <path
                                            d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z" />
                                    </svg>
                                </div>
                                <h4>Filter</h4>
                            </div>
                            {{-- <div class="app-print">
                                <div class="print-i">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" />
                                        <path
                                            d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                    </svg>
                                </div>
                                <h4>Print</h4>
                            </div> --}}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead class="overtime-data border-custom">
                                            <tr>
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 10%;">Tanggal</th>
                                                <th class="text-center" style="width: 15%;">Nama Karyawan</th>
                                                <th class="text-center" style="width: 20%;">Uraian Kegiatan</th>
                                                <th class="text-center" style="width: 15%;">Bukti Lembur</th>
                                                <th class="text-center" style="width: 10%;">Status</th>
                                                <th class="text-center" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="overtime-data">
                                                <td>1</td>
                                                <td style="text-align: center">04/09/2025</td>
                                                <td style="text-align: center">Anonymous</td>
                                                <td>
                                                    <div class="space-td">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        Lampiran</a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-warning badge-custom"
                                                        data-toggle="modal" data-target="#trackingStatus1">Menunggu</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="overtime-action space-td">
                                                        <button type="button" class="btn btn-success"
                                                            onclick="openApprovedModal()">Setujui</button>
                                                        <button type="button" class="btn btn-danger"
                                                            onclick="openSuspendModal()">Tolak</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="overtime-data">
                                                <td>2</td>
                                                <td style="text-align: center">04/09/2025</td>
                                                <td style="text-align: center">Anonymous</td>
                                                <td>
                                                    <div class="space-td">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        Lampiran</a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-success badge-custom"
                                                        data-toggle="modal" data-target="#trackingStatus2">Disetujui</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="overtime-data">
                                                <td>3</td>
                                                <td style="text-align: center">04/09/2025</td>
                                                <td style="text-align: center">Anonymous</td>
                                                <td>
                                                    <div class="space-td">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        Lampiran</a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-danger badge-custom"
                                                        data-toggle="modal" data-target="#trackingStatus3">Ditolak</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 rounded-4">
                <div class="filter-header">
                    <p>Filter By:</p>
                    <button class="modal-close" onclick="closeFilterModal()">✕</button>
                </div>
                <div class="modal-body">
                    <div class="date-reset">
                        <p>Rentang Tanggal</p>
                        <a href="#" class="text-danger" style="font-weight: bold; text-decoration: none;">Reset</a>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label small">From</label>
                            <input type="text" id="startDate" class="form-control" name="start_date"
                                placeholder="Tanggal awal" />
                        </div>
                        <div class="col">
                            <label class="form-label small">To</label>
                            <input type="text" id="endDate" class="form-control" name="end_date"
                                placeholder="Tanggal akhir" />
                        </div>
                    </div>
                    <div class="status-search">
                        <div class="search-box">
                            <select class="select2 form-control">
                                <option value=" ">Status</option>
                                <option value="option1">Disetujui</option>
                                <option value="option2">Menunggu</option>
                                <option value="option3">Ditolak</option>
                            </select>
                        </div>
                        <div class="filter-search">
                            <div class="search-box">
                                <select class="select2 form-control">
                                    <option value=" " disabled>Pilih karyawan</option>
                                    <option value="option1">Akbar</option>
                                    <option value="option2">Wahyu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top: 1rem">
                    <button type="button" class="btn btn-danger btn-footer" onclick="closeFilterModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-x-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                        </svg>
                        Batal
                    </button>
                    <button type="button" class="btn btn-success btn-footer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path
                                d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Lampiran</h5>
                    <button type="button" class="modal-close" onclick="closeFotoModal()">✕</button>
                </div>
                <div class="modal-body text-center">
                    <img src="8a9ac794-0fb2-43df-a1e6-d31b0de643d4.png" alt="Foto"
                        class="img-fluid rounded shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackingStatus1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="padding: 0.5rem;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Riwayat Status Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 15%" scope="col">Pejabat</th>
                                <th class="text-center" style="width: 15%" scope="col">Status</th>
                                <th class="text-center" style="width: 30%" scope="col">Tanggal</th>
                                <th style="width: 40%" scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>PM</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>HRD</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>Direktur</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                </td>
                                <td>
                                    <p class="text-center">-</p>
                                </td>
                                <td>
                                    <p>-</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex" style="justify-content: right;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackingStatus2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="padding: 0.5rem;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Riwayat Status Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 15%" scope="col">Pejabat</th>
                                <th class="text-center" style="width: 15%" scope="col">Status</th>
                                <th class="text-center" style="width: 30%" scope="col">Tanggal</th>
                                <th style="width: 40%" scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>PM</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>HRD</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>Direktur</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur valid</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex" style="justify-content: right;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackingStatus3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="padding: 0.5rem;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Riwayat Status Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 15%" scope="col">Pejabat</th>
                                <th class="text-center" style="width: 15%" scope="col">Status</th>
                                <th class="text-center" style="width: 30%" scope="col">Tanggal</th>
                                <th style="width: 40%" scope="col">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>PM</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-danger badge-custom">Ditolak</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur tidak valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>HRD</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-danger badge-custom">Ditolak</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur tidak valid</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td>Direktur</td>
                                <td class="text-center">
                                    <span class="badge badge-pill badge-danger badge-custom">Ditolak</span>
                                </td>
                                <td class="text-center">27/10/2025</td>
                                <td>Bukti lembur tidak valid</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer d-flex" style="justify-content: right;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="approvedModal" class="modal-overlay">
        <div class="modal-check-box">
            <div class="modal-check-icons">
                <div class="modal-check">
                    <div class="check-circle"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg>
                </div>
                <button class="modal-close" onclick="closeApprovedModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Setujui Pengajuan Lembur</h2>
                <p class="modal-text">
                    Apakah Anda yakin ingin menyetujui pengajuan lembur ini?<br>
                    Berikan alasan Anda:
                </p>
                <textarea name="form-control" id="approval-reason"></textarea>
            </div>
            <div class="modal-actions" style="justify-content: end; margin-bottom: 1rem">
                <button class="btn btn-danger" onclick="confirmApproved()">Konfirmasi</button>
            </div>
        </div>
    </div>

    <div id="suspendModal" class="modal-overlay">
        <div class="modal-check-box">
            <div class="modal-check-icons">
                <div class="modal-check3">
                    <div class="check-circle3"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg>
                </div>
                <button class="modal-close" onclick="closeSuspendModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Tolak Pengajuan Lembur</h2>
                <p class="modal-text">
                    Apakah Anda yakin ingin menolak pengajuan lembur ini?<br>
                    Berikan alasan Anda:
                </p>
                <textarea name="form-control" id="approval-reason"></textarea>
            </div>
            <div class="modal-actions" style="justify-content: end; margin-bottom: 1rem">
                <button class="btn btn-danger" onclick="confirmSuspend()">Konfirmasi</button>
            </div>
        </div>
    </div>
@endsection

@push('customScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        let myModal;

        function openFilterModal() {
            const modalEl = document.getElementById('filterModal');
            myModal = new bootstrap.Modal(modalEl, {
                backdrop: true,
                keyboard: true
            });
            myModal.show();
        }

        function closeFilterModal() {
            if (myModal) {
                myModal.hide();
            } else {
                const modalEl = document.getElementById('filterModal');
                const existingInstance = bootstrap.Modal.getInstance(modalEl);
                if (existingInstance) {
                    existingInstance.hide();
                }
            }
        }

        let fotoModalInstance;

        function openFotoModal() {
            const modalEl = document.getElementById('fotoModal');
            fotoModalInstance = new bootstrap.Modal(modalEl);
            fotoModalInstance.show();
        }

        function closeFotoModal() {
            if (fotoModalInstance) {
                fotoModalInstance.hide();
            }
        }

        function openApprovedModal() {
            document.getElementById('approvedModal').classList.add('show');
        }

        function closeApprovedModal() {
            document.getElementById('approvedModal').classList.remove('show');
        }

        function confirmApproved() {
            alert('Lembur Disetujui! (simulasi frontend)');
            closeApprovedModal();
        }

        function openSuspendModal() {
            document.getElementById('suspendModal').classList.add('show');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.remove('show');
        }

        function confirmSuspend() {
            alert('Lembur Ditolak! (simulasi frontend)');
            closeSuspendModal();
        }

        $('.select2').select2({
            dropdownParent: $('#filterModal')
        });

        $('#startDate').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            autoUpdateInput: false,
        });

        $('#endDate').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true,
            autoUpdateInput: false,
        });
    </script>
@endpush
