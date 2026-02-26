{{-- resources/views/hrd/add-employment-contract.blade.php --}}
@extends('layouts.app-hrd')

@section('title', 'Edit Kontrak Kerja')

@push('style')
    <link rel="stylesheet" href="{{ asset('css/hrd/edit-employment-contract.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Kontrak Kerja</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-hrd') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Edit Kontrak Kerja</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="title-employment-edit">Formulir Kontrak Kerja</h2>
                        <p class="subtitle-employment-edit">Isi formulir kontrak kerja dengan benar!</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-danger text-white fw-bold">
                        Data Pegawai
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employment-contract-hrd') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="nama" class="form-label fw-semibold">Nama Karyawan</label>
                                    <input type="text" name="nama" id="nama" class="form-control border-danger"
                                        placeholder="Masukkan nama karyawan" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kontrak</label>
                                    <div class="d-flex gap-2">
                                        <input type="date" name="tanggal_mulai" class="form-control border-danger mr-2"
                                            placeholder="Tanggal Mulai" required>
                                        <input type="date" name="tanggal_selesai" class="form-control border-danger"
                                            placeholder="Tanggal Selesai" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light px-4 shadow-sm mr-2">Simpan
                                    Draft</button>
                                <button type="submit" class="btn btn-filter px-4 shadow-sm">Buat Kontrak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
