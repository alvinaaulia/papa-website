@extends('layouts.app-login')

@section('title', 'Login')

@section('main')
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <img src="{{ asset('images/logo-mascitra.svg') }}" alt="Mascitra Logo" class="brand-logo">
            </div>

            <div class="title-login">
                <h2 class="welcome">Selamat datang di <strong>PAPA</strong></h2>
                <p class="subtitle">
                    (Manajemen Pegawai) / Manajemen Informasi Kepegawaian Mascitra.com
                </p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- PERBAIKAN DISINI: Gunakan route yang benar --}}
            <form method="POST" action="{{ route('login.post') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Masukkan email Anda"
                    >
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Masukkan password Anda"
                    >
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat Saya</label>
                </div>

                <div class="form-actions">
                    <a href="#" class="forgot-password">Lupa Password?</a>
                    <button type="submit" class="btn-login">
                        Login
                    </button>
                </div>

                <div class="login-info">
                    <p class="text-center mt-3">
                        <small>Sistem akan mengarahkan Anda ke dashboard sesuai role</small>
                    </p>
                </div>
            </form>
        </div>

        <div class="login-right">
            <div class="overlay-text">
                <h2>Have A Nice Day!</h2>
                <p>Mascitra.com</p>
            </div>
        </div>
    </div>
@endsection