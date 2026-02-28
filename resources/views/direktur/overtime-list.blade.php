@extends('layouts.app-director')

@section('title', 'Daftar Lembur Karyawan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Lembur</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Daftar Lembur</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave">
                        <div class="title-overtime-histories">
                            <div class="title-lead">
                                Daftar Lembur
                            </div>
                            <div class="sub-head" style="font-size: 1rem; padding-left: 20px; margin-bottom: 2rem;">
                                Daftar Lembur Karyawan
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="header-overtime-histories">
                                <h4>Tabel Daftar Lembur</h4>
                            </div>
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="overtime-data">
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 10%;">Tanggal</th>
                                                <th class="text-center" style="width: 15%; text-align: left;">Nama</th>
                                                <th class="text-center" style="width: 15%;">Jam Lembur</th>
                                                <th class="text-center" style="width: 12%;">Jumlah Jam Lembur</th>
                                                <th class="text-center" style="width: 18%;">Uraian Kegiatan</th>
                                                <th class="text-center" style="width: 13%;">Bukti Lembur</th>
                                                <th class="text-center" style="width: 10%;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- <tr class="overtime-data text-center">
                                                <td>1</td>
                                                <td>04/09/2025</td>
                                                <td>Anonymous</td>
                                                <td>17.00 s.d 22.00 WIB</td>
                                                <td>5 Jam</td>
                                                <td>
                                                    <div class="space-td" style="text-align: left">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        foto</a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-secondary badge-custom">Draft</span>
                                                </td>
                                            </tr> --}}
                                            <tr class="overtime-data text-center">
                                                <td>1</td>
                                                <td>04/09/2025</td>
                                                <td>Anonymous</td>
                                                <td>17.00 s.d 22.00 WIB</td>
                                                <td>5 Jam</td>
                                                <td>
                                                    <div class="space-td" style="text-align: left">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        foto</a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill badge-warning badge-custom" data-toggle="modal" data-target="#trackingStatus1">Menunggu</span>
                                                </td>
                                            </tr>
                                            <tr class="overtime-data text-center">
                                                <td>2</td>
                                                <td>04/09/2025</td>
                                                <td>Anonymous</td>
                                                <td>17.00 s.d 22.00 WIB</td>
                                                <td>5 Jam</td>
                                                <td>
                                                    <div class="space-td" style="text-align: left">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        foto</a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill badge-success badge-custom" data-toggle="modal" data-target="#trackingStatus2">Disetujui</span>
                                                </td>
                                            </tr>
                                            <tr class="overtime-data text-center">
                                                <td>3</td>
                                                <td>04/09/2025</td>
                                                <td>Anonymous</td>
                                                <td>17.00 s.d 22.00 WIB</td>
                                                <td>5 Jam</td>
                                                <td>
                                                    <div class="space-td" style="text-align: left">
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam at sem
                                                        vulputate fringilla non nec justo.
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="evidence" style="text-decoration: none"
                                                        onclick="openFotoModal()">Buka
                                                        foto</a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-danger badge-custom" data-toggle="modal" data-target="#trackingStatus3">Ditolak</span>
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

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-delete-box">
            <div class="modal-icons">
                <div class="modal-trash">
                    <div class="trash-circle"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path
                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                    </svg>
                </div>
                <button class="modal-close" onclick="closeDeleteModal()">✕</button>
            </div>
            <div class="modal-text">
                <h2 class="modal-title">Hapus Pelaporan Lembur</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus pelaporan lembur ini?<br>
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="modal-actions" style="gap: 2rem; margin: 2rem 0px 1rem 0px;">
                <button class="btn btn-secondary btn-footer" style="padding: 0.5rem 3rem"
                    onclick="closeDeleteModal()">Batal</button>
                <button class="btn btn-danger btn-footer" style="padding: 0.5rem 3rem"
                    onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Bukti Foto</h5>
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
                                    <p >-</p>
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
@endsection

@push('scripts')
    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        function confirmDelete() {
            alert('Data dihapus (simulasi frontend)');
            closeDeleteModal();
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
    </script>
@endpush
