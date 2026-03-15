@extends('layouts.app-hrd')

@section('title', 'Komponen Gaji')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">

    <style>
        .page-subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-top: -5px;
        }

        .filter-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-left {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input {
            min-width: 250px;
        }

        .component-table thead th {
            font-weight: 600;
            font-size: 13px;
            color: #6c757d;
        }

        .component-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .component-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
        }

        .badge-earning {
            background-color: #d4edda;
            color: #155724;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-deduction {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-inactive {
            background-color: #e2e3e5;
            color: #6c757d;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .pagination-custom .page-link {
            border-radius: 8px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Daftar Komponen Gaji</h1>
                    <div class="page-subtitle" style="margin-top: 1px;">
                        Kelola komponen earning dan deduction untuk perhitungan gaji
                    </div>
                </div>
                <a href="{{ route('hrd.add-components') }}" class="btn btn-danger">
                    <i class="fas fa-plus"></i> Tambah Komponen
                </a>
            </div>

            <div class="section-body">

                <!-- Filter & Action -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <div class="d-flex align-items-center">
                        <select class="form-control mr-3">
                            <option>All Status</option>
                            <option>Aktif</option>
                            <option>Non-aktif</option>
                        </select>

                        <div class="input-group search-box">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search rules...">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover component-table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>NAMA KOMPONEN</th>
                                        <th>KATEGORI</th>
                                        <th>STATUS</th>
                                        <th class="text-right">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($components as $component)
                                        <tr>
                                            <td>
                                                <div class="component-item">
                                                    <div class="component-icon"
                                                        style="background:
                            {{ $component->component_type === 'EARNING' ? '#28a745' : '#dc3545' }};">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $component->component_name }}</strong><br>
                                                        <small class="text-muted">
                                                            {{ $component->description ?? '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- KATEGORI --}}
                                            <td>
                                                @if ($component->component_type === 'EARNING')
                                                    <span class="badge-earning">+ Earning</span>
                                                @else
                                                    <span class="badge-deduction">- Deduction</span>
                                                @endif
                                            </td>

                                            {{-- STATUS --}}
                                            <td>
                                                @if ($component->is_active)
                                                    <span class="badge-active">● Aktif</span>
                                                @else
                                                    <span class="badge-inactive">● Non-aktif</span>
                                                @endif
                                            </td>

                                            {{-- AKSI --}}
                                            <td class="text-right">
                                                <a href="{{ url('/hrd/components/' . $component->component_id) }}"
                                                    class="text-primary font-weight-bold">
                                                    Detail <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Belum ada komponen yang dibuat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="table-footer">
                    <div>
                        <small class="text-muted">
                            Showing {{ $components->firstItem() ?? 0 }}
                            to {{ $components->lastItem() ?? 0 }}
                            of {{ $components->total() }} components
                        </small>
                    </div>

                    <div>
                        {{ $components->links() }}
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
