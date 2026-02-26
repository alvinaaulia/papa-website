@extends('layouts.app-pm')

@section('title', 'Tambah Kegiatan Harian PM')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pm/add-daily-activities-pm.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Kegiatan Harian PM</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard-PM') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('add-daily-activities-pm') }}">Kegiatan Harian PM</a>
                    </div>
                    <div class="breadcrumb-item">Tambah Kegiatan Harian PM</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="form-main-title-pm">Tambah Kegiatan Harian PM</h2>
                        <p class="form-subtitle-pm">Lengkapi form kegiatan harian</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-container">
                                    <form id="daily-activity-form" action="{{ route('add-daily-activities-pm') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-section-pm">
                                            <h3 class="section-title-pm">Tanggal</h3>
                                            <div class="date-input-container">
                                                <input type="date" class="form-control date-input" id="activity-date"
                                                    name="activity_date" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-section">
                                            <h3 class="section-title-pm">Daftar Kegiatan Harian</h3>
                                            <div class="activities-container">
                                                <div class="activity-items" id="activity-items-container">
                                                    <!-- Activity items will be dynamically added here -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-filter">Simpan</button>
                                        </div>
                                    </form>
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
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <script>
        let activityCounter = 0;

        document.addEventListener('DOMContentLoaded', function() {
            addActivityField();
        });

        function setPriority(element, value) {
            const dropdown = element.closest('.dropdown');
            const button = dropdown.querySelector('button');
            const icon = element.querySelector('i').outerHTML;
            const text = element.textContent.trim();

            // Ubah isi tombol dropdown agar menampilkan pilihan user
            button.innerHTML = `${icon} ${text}`;

            // Set nilai hidden input agar dikirim ke server
            dropdown.querySelector('.priority-value').value = value;
        }

        function addActivityField() {
            const container = document.getElementById('activity-items-container');
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            activityItem.innerHTML = `
                <div class="row align-items-start">
                    <div class="col-md-8">
                        <textarea class=" activity-textarea" name="activities[${activityCounter}][description]" placeholder="Uraian kegiatan harian anda" rows="2" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <div class="priority-action-container">
                            <div class="priority-dropdown-container">
                                <div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle w-100 text-left"
                                            type="button"
                                            data-toggle="dropdown"
                                            data-display="static"
                                            aria-expanded="false">
                                        <i class="fas fa-flag text-secondary mr-2"></i> Pilih Prioritas
                                    </button>
                                    <div class="dropdown-menu w-100 dropdown-menu-end">
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="setPriority(this, 'urgent')">
                                        <i class="fas fa-flag text-danger mr-2"></i> Urgent
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="setPriority(this, 'high')">
                                        <i class="fas fa-flag text-primary mr-2"></i> High
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="setPriority(this, 'normal')">
                                        <i class="fas fa-flag text-success mr-2"></i> Normal
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="setPriority(this, 'low')">
                                        <i class="fas fa-flag text-muted mr-2"></i> Low
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="setPriority(this, 'clear')">
                                        <i class="fas fa-ban text-secondary mr-2"></i> Clear
                                        </a>
                                    </div>
                                    <input type="hidden" name="activities[${activityCounter}][priority]" class="priority-value" value="">
                                </div>


                            </div>
                            <div class="action-buttons d-flex gap-2">
                                <button type="button" class="btn btn-filter btn-action d-flex align-items-center justify-content-center"
                                    onclick="addActivityField()">
                                    <i class="fas fa-plus"></i>
                                </button>

                                <button type="button" class="btn btn-filter btn-action d-flex align-items-center justify-content-center"
                                    onclick="removeActivityField(this)" ${activityCounter === 0 ? 'disabled' : ''}>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            `;
            container.appendChild(activityItem);
            activityCounter++;

            // Update minus buttons state
            updateMinusButtons();
        }

        function removeActivityField(button) {
            const activityItem = button.closest('.activity-item');
            if (activityItem) {
                activityItem.remove();
                activityCounter--;
                updateMinusButtons();
            }
        }

        function updateMinusButtons() {
            const activityItems = document.querySelectorAll('.activity-item');
            const minusButtons = document.querySelectorAll('.btn-minus');

            if (activityItems.length === 1) {
                minusButtons[0].disabled = true;
            } else {
                minusButtons.forEach(button => {
                    button.disabled = false;
                });
            }
        }
    </script>
@endpush
