@extends('layouts.app-login')

@section('title', 'Lupa Password')

@section('main')
    <section class="body-forgotpass">
        <div class="forgot-pass">
            <div class="logo-mcit">
                <img src="{{ asset('images/logo-mascitra.svg') }}" alt="Mascitra Logo">
            </div>

            <div class="card-forgotpass">
                <h2 class="title-forgotpass">Lupa Password</h2>
                <p class="subtitle-forgotpass">Tulis Email kamu untuk reset password kamu.</p>

                <form id="resetForm">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Masukkan email kamu" required>

                    <div id="successMessage" class="success hidden">
                        Kami sudah mengirim surel yang berisi tautan untuk mereset kata sandi Anda!
                    </div>

                    <button type="submit" class="btn-forgotpass">Lupa Password</button>
                </form>
            </div>

            <div class="footer-forgotpass">
                <footer>
                    <p>Copyright © Mascitra.com</p>
                </footer>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById("resetForm");
        const successMessage = document.getElementById("successMessage");

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            successMessage.classList.remove("hidden");
        });
    </script>
@endpush
