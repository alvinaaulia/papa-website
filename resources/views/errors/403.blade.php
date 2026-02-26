@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-danger">
                    <h3>403 - Akses Ditolak</h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-ban fa-5x text-danger mb-4"></i>
                    <h4 class="mb-3">Anda tidak memiliki izin untuk mengakses halaman ini</h4>
                    <p class="mb-4">Role Anda: <strong>{{ Auth::user()->getRoleDisplayAttribute() }}</strong></p>
                    <a href="{{ url()->previous() }}" class="btn btn-primary mr-2">Kembali</a>
                    <a href="{{ route('dashboard.'.Auth::user()->role) }}" class="btn btn-secondary">Ke Dashboard Saya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection