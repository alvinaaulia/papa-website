@extends('layouts.app-director')

@section('title', 'Jenis Cuti')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Jenis Cuti</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-director') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Jenis Cuti</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="title-overtime-histories">
                        <div class="title-lead">
                            Daftar Jenis Cuti
                        </div>
                        <div class="sub-head" style="font-size: 1rem; padding-left: 20px; margin-bottom: 2rem;">
                            Daftar Jenis Cuti Perusahaan
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="header-overtime-histories">
                                <h4>Tabel Daftar Jenis Cuti</h4>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary btn-sm" id="refreshButton" style="margin-bottom: 1rem">
                                    <i class="fas fa-sync-alt"></i> Refresh Data
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%;">No.</th>
                                                <th class="text-center" style="width: 25%;">Jenis Cuti</th>
                                                <th class="text-center" style="width: 70%;">Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="leaveTypesTableBody">
                                        </tbody>
                                    </table>
                                </div>
                                <div id="emptyState" class="text-center" style="display: none;">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data jenis cuti</p>
                                </div>
                                <div id="loadingIndicator" class="text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                                <div id="errorMessage" class="alert alert-danger" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="errorText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/DataMaster/leave-types.js') }}"></script>
@endpush

@push('customScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
@endpush
