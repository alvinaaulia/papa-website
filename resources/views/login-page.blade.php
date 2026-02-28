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

            <form method="POST" class="login-form">
                @csrf
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>

                <div class="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span for="remember">Ingat Saya</span>
                </div>

                <div class="form-actions">
                    <a href="{{ route('forgot-password') }}" class="forgot-password">Lupa Password?</a>
                    <button class="btn-login" type="submit" id="login-button">Login</button>
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

@push('scripts')
    <script src="{{ asset('js/page/auth-login.js') }}"></script>
@endpush
