@extends('layouts.app-hrd')

@section('title', 'Riwayat Cuti')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Cuti</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Riwayat Cuti</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-leave" style="padding-left: 20px;">
                        <div class="title-lead">
                            Riwayat Cuti
                        </div>
                        <div class="sub-lead" style="font-size: 1rem; padding-left: 20px; margin-bottom: 2rem;">
                            Daftar Riwayat Cuti Kamu
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="header-histories">
                                <h4>Tabel Daftar Cuti Kamu</h4>
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
                                                <th class="text-center" style="width: 5%">No.</th>
                                                <th class="text-center" style="width: 20%">Nama</th>
                                                <th class="text-center">Detail</th>
                                                <th class="text-center" style="width: 20%">Status</th>
                                                <th class="text-center" style="width: 10%">Aksi</th>
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
                                                    <table class="table table-bordered" style="margin: 0px;">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Pejabat</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 50%">PM</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-secondary badge-custom">Draft</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-secondary badge-custom">Draft</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-secondary badge-custom">Draft</span>
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
                                                            <a href="{{ route('edit-leave-hrd') }}" class="overtime-action"
                                                                style="text-decoration: none">
                                                                <button type="button"
                                                                    class="btn btn-secondary">Edit</button>
                                                            </a>
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="openDeleteModal()">Hapus</button>
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
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-warning badge-custom">Menunggu</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td class="text-center">
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
                                                        <a href="{{ route('leave-details-hrd') }}" class="overtime-action"
                                                            style="text-decoration: none;">
                                                            <button type="button"
                                                                class="btn btn-primary">Rincian</button>
                                                        </a>
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
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">HRD</td>
                                                                <td class="text-center">
                                                                    <span
                                                                        class="badge badge-pill badge-success badge-custom">Disetujui</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%">Direktur</td>
                                                                <td class="text-center">
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
                                                        <a href="{{ route('leave-details-hrd') }}" class="overtime-action"
                                                            style="text-decoration: none;">
                                                            <button type="button"
                                                                class="btn btn-primary">Rincian</button>
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
                <h2 class="modal-title">Hapus Pengajuan Cuti</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus pengajuan cuti ini?<br>
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
@endsection

@push('scripts')
    @include('hrd.pdf-leave')
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
    </script>
@endpush
