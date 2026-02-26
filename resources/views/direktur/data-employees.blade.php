@extends('layouts.app-director')

@section('title', 'Data Karyawan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Karyawan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Karyawan</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="head-approval">
                        <div class="title-overtime-approval">
                            <div class="title-lead">
                                Data Karyawan
                            </div>
                            <div class="sub-head" style="font-size: 1rem; padding-left: 20px;">
                                Daftar Data Karyawan
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
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="overtime-data">
                                                <th class="text-overtime" style="width: 5%;">No.</th>
                                                <th class="text-overtime" style="width: 15%;">NIP</th>
                                                <th class="text-overtime" style="width: 20%;">Nama Karyawan</th>
                                                <th class="text-overtime" style="width: 15%;">Jabatan</th>
                                                <th class="text-overtime" style="width: 10%;">Status</th>
                                                <th class="text-overtime" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center overtime-data">
                                                <td>1</td>
                                                <td>123456789</td>
                                                <td>Anonymous</td>
                                                <td>Karyawan</td>
                                                <td>
                                                    <span class="badge badge-pill badge-success badge-custom">Aktif</span>
                                                </td>
                                                <td>
                                                    <div class="overtime-action space-td">
                                                        <a href="{{ route('employee-details-director') }}" class="overtime-action" style="text-decoration: none">
                                                            <button type="button" class="btn btn-primary" >Rincian</button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="text-center overtime-data">
                                                <td>2</td>
                                                <td>123456789</td>
                                                <td>Anonymous</td>
                                                <td>Karyawan</td>
                                                <td>
                                                    <span class="badge badge-pill badge-success badge-custom">Aktif</span>
                                                </td>
                                                <td>
                                                    <div class="overtime-action space-td">
                                                        <a href="{{ route('employee-details-director') }}" class="overtime-action">
                                                            <button type="button" class="btn btn-primary">Rincian</button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="text-center overtime-data">
                                                <td>3</td>
                                                <td>123456789</td>
                                                <td>Anonymous</td>
                                                <td>Karyawan</td>
                                                <td>
                                                    <span class="badge badge-pill badge-danger badge-custom">Non Aktif</span>
                                                </td>
                                                <td>
                                                    <div class="overtime-action space-td">
                                                        <a href="{{ route('employee-details-director') }}" class="overtime-action">
                                                            <button type="button" class="btn btn-primary">Rincian</button>
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
                <h2 class="modal-title">Hapus Data Karyawan</h2>
                <p class="modal-text" style="margin: 0px">
                    Yakin ingin menghapus data karyawan ini?<br>
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
                                <option value="option1">Aktif</option>
                                <option value="option3">Non Aktif</option>
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
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
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
