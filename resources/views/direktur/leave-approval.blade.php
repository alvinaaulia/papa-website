@extends('layouts.app-director')

@section('title', 'Persetujuan Cuti')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Persetujuan Cuti</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Persetujuan Cuti</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px;">
                        <div class="title-lead">
                            Persetujuan Cuti
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px; margin-bottom: 2rem;">
                            Daftar pengajuan cuti karyawan
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="header-histories">
                                <h4>Tabel Daftar Persetujuan Cuti</h4>
                                <div class="per-page-filter">
                                    <span>Menampilkan</span>
                                    <select>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                    </select>
                                    <span>per halaman</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 20%;">Nama</th>
                                                <th class="text-center">Detail</th>
                                                <th class="text-center" style="width: 20%;">Status</th>
                                                <th class="text-center" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">Anonymous</td>
                                                <td>
                                                    <div class="leave-detail">
                                                        <div class="leave-type">
                                                            <h4>Selasa, 2-09-2025 s.d Kamis 4-09- 2025</h4>
                                                            <p>Cuti Tahunan (Tahun Kontrak)</p>
                                                        </div>
                                                        <div class="leave-description">
                                                            <h4>Alasan:</h4>
                                                            <p>Acara keluarga di Dubai</p>
                                                        </div>
                                                        <div class="leave-note">
                                                            <h4>Catatan Cuti:</h4>
                                                            <p>Tidak ada data</p>
                                                        </div>
                                                        <div class="leave-address">
                                                            <h4>Alamat Selama Cuti:</h4>
                                                            <p>Burj Khalifah, Dubai</p>
                                                        </div>
                                                        <div class="leave-number">
                                                            <h4>No. Telepon</h4>
                                                            <p>08123456789</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 0px 0px 0px 0px">
                                                    <table class="table table-bordered" style="margin: 0px">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Pejabat</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 50%">PM</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row" colspan="2"
                                                                    style="padding: 1rem 1.5rem;">
                                                                    <p style="margin: 0">Formulir</p>
                                                                    <button type="button"
                                                                        class="btn btn-danger mt-1 mb-2 btn-pdf-leave"><i
                                                                            class="fas fa-print mr-2"></i>Cetak
                                                                        Cuti</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="overtime-action">
                                                            <button type="button" class="btn btn-warning"
                                                                onclick="openRevisiModal()">Revisi</button>
                                                            <button type="button" class="btn btn-success"
                                                                onclick="openApprovedModal()">Setujui</button>
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="openSuspendModal()">Tangguhkan</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td class="text-center">Anonymous</td>
                                                <td>
                                                    <div class="leave-detail">
                                                        <div class="leave-type">
                                                            <h4>Selasa, 2-09-2025 s.d Kamis 4-09- 2025</h4>
                                                            <p>Cuti Tahunan (Tahun Kontrak)</p>
                                                        </div>
                                                        <div class="leave-description">
                                                            <h4>Alasan:</h4>
                                                            <p>Acara keluarga di Dubai</p>
                                                        </div>
                                                        <div class="leave-note">
                                                            <h4>Catatan Cuti:</h4>
                                                            <p>Tidak ada data</p>
                                                        </div>
                                                        <div class="leave-address">
                                                            <h4>Alamat Selama Cuti:</h4>
                                                            <p>Burj Khalifah, Dubai</p>
                                                        </div>
                                                        <div class="leave-number">
                                                            <h4>No. Telepon</h4>
                                                            <p>08123456789</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 0px 0px 0px 0px">
                                                    <table class="table table-bordered" style="margin: 0px">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 50%">PM</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row" colspan="2"
                                                                    style="padding: 1rem 1.5rem;">
                                                                    <p style="margin: 0">Formulir</p>
                                                                    <button type="button"
                                                                        class="btn btn-danger mt-1 mb-2 btn-pdf-leave"><i
                                                                            class="fas fa-print mr-2"></i>Cetak
                                                                        Cuti</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="overtime-action">
                                                            <button type="button" class="btn btn-warning"
                                                                onclick="openRevisiModal()">Revisi</button>
                                                            <button type="button" class="btn btn-success"
                                                                onclick="openApprovedModal()">Setujui</button>
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="openSuspendModal()">Tangguhkan</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td class="text-center">Anonymous</td>
                                                <td>
                                                    <div class="leave-detail">
                                                        <div class="leave-type">
                                                            <h4>Selasa, 2-09-2025 s.d Kamis 4-09- 2025</h4>
                                                            <p>Cuti Tahunan (Tahun Kontrak)</p>
                                                        </div>
                                                        <div class="leave-description">
                                                            <h4>Alasan:</h4>
                                                            <p>Acara keluarga di Dubai</p>
                                                        </div>
                                                        <div class="leave-note">
                                                            <h4>Catatan Cuti:</h4>
                                                            <p>Tidak ada data</p>
                                                        </div>
                                                        <div class="leave-address">
                                                            <h4>Alamat Selama Cuti:</h4>
                                                            <p>Burj Khalifah, Dubai</p>
                                                        </div>
                                                        <div class="leave-number">
                                                            <h4>No. Telepon</h4>
                                                            <p>08123456789</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 0px 0px 0px 0px">
                                                    <table class="table table-bordered" style="margin: 0px">
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 50%">PM</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row" colspan="2"
                                                                    style="padding: 1rem 1.5rem;">
                                                                    <p style="margin: 0">Formulir</p>
                                                                    <button type="button"
                                                                        class="btn btn-danger mt-1 mb-2 btn-pdf-leave"><i
                                                                            class="fas fa-print mr-2"></i>Cetak
                                                                        Cuti</button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <a href="{{ route('approval-details-director') }}"
                                                            class="overtime-action">
                                                            <button type="button" class="btn btn-primary"
                                                                style="text-decoration: none;">Rincian</button>
                                                        </a>
                                                    </div>
                                                </td>
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
                <h2 class="modal-title">Setujui Pengajuan Cuti</h2>
                <p class="modal-text">
                    Apakah Anda yakin ingin menyetujui pengajuan cuti ini?<br>
                    Berikan alasan Anda:
                </p>
                <textarea name="form-control" id="approval-reason"></textarea>
            </div>
            <div class="modal-actions" style="justify-content: end; margin-bottom: 1rem">
                <button class="btn btn-danger" onclick="confirmApproved()">Konfirmasi</button>
            </div>
        </div>
    </div>

    <div id="revisiModal" class="modal-overlay">
        <div class="modal-check-box">
            <div class="modal-check-icons">
                <div class="modal-check2">
                    <div class="check-circle2"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-check-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg>
                </div>
                <button class="modal-close" onclick="closeRevisiModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Revisi Pengajuan Cuti</h2>
                <p class="modal-text">
                    Apakah Anda yakin ingin merevisi pengajuan cuti ini?<br>
                    Berikan alasan Anda:
                </p>
                <textarea name="form-control" id="approval-reason"></textarea>
            </div>
            <div class="modal-actions" style="justify-content: end; margin-bottom: 1rem">
                <button class="btn btn-danger" onclick="confirmRevisi()">Konfirmasi</button>
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
                <h2 class="modal-title">Tangguhkan Pengajuan Cuti</h2>
                <p class="modal-text">
                    Apakah Anda yakin ingin menangguhkan pengajuan cuti ini?<br>
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

@push('scripts')
    @include('direktur.pdf-leave')
    <script>
        function openApprovedModal() {
            document.getElementById('approvedModal').classList.add('show');
        }

        function closeApprovedModal() {
            document.getElementById('approvedModal').classList.remove('show');
        }

        function confirmApproved() {
            alert('Cuti Disetujui! (simulasi frontend)');
            closeApprovedModal();
        }


        function openRevisiModal() {
            document.getElementById('revisiModal').classList.add('show');
        }

        function closeRevisiModal() {
            document.getElementById('revisiModal').classList.remove('show');
        }

        function confirmRevisi() {
            alert('Cuti Direvisi! (simulasi frontend)');
            closeRevisiModal();
        }



        function openSuspendModal() {
            document.getElementById('suspendModal').classList.add('show');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.remove('show');
        }

        function confirmSuspend() {
            alert('Cuti Ditangguhkan! (simulasi frontend)');
            closeSuspendModal();
        }
    </script>
@endpush
