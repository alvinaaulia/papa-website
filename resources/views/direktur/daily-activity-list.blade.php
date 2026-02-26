@extends('layouts.app-director')

@section('title', 'Daftar Kegiatan Harian PM')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/director/daily-activity-list.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Kegiatan Harian PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">Daftar Kegiatan Harian PM</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h2 class="list-director-title">Daftar Kegiatan Harian PM</h2>
                        <p class="list-director-subtitle">Daftar Kegiatan Harian</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="table-title">Daftar Kegiatan Harian</h5>
                        <hr class="table-divider">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" style="width: 10%">No.</th>
                                        <th scope="col">Tanggal Project Diberikan</th>
                                        <th scope="col">Nama Project Manager</th>
                                        <th scope="col" style="width: 15%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (range(1, 4) as $i)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>04/09/2025</td>
                                            <td>Septian Iqbal Pratama</td>
                                            <td>
                                                <a href="{{ route('daily-activity-details') }}"
                                                    class="btn btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
