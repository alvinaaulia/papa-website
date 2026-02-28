class PresenceData {
    constructor() {
        // Inisialisasi base URL dan elemen DOM
        this.baseUrl = '/api/presence';
        this.employeesUrl = '/api/data-master/users';
        this.tableBody = document.querySelector('#kegiatanTable tbody');
        this.paginationContainer = document.querySelector('.pagination');
        
        // Inisialisasi variabel state
        this.currentPage = 1;
        this.perPage = 10;
        this.totalPages = 0;
        this.currentFilters = {};
        this.employees = [];
        
        // Jalankan inisialisasi
        this.init();
    }

    /**
     * Fungsi inisialisasi utama
     * Memuat data karyawan, data presensi, dan setup event listeners
     */
    init() {
        this.loadEmployees();
        this.loadPresences();
        this.setupEventListeners();
    }

    /**
     * Memuat data karyawan dari API untuk filter
     */
    async loadEmployees() {
        try {
            const response = await window.fetchWithAuth(this.employeesUrl, { method: 'GET' });
            const result = await response.json();
            
            if (result.status === 'success') {
                this.employees = result.data;
                this.populateEmployeeFilter();
            }
        } catch (error) {
            console.error('Error loading employees:', error);
            this.employees = [];
            this.populateEmployeeFilter();
        }
    }

    /**
     * Mengisi dropdown filter dengan data karyawan
     * Fallback ke data statis jika API gagal
     */
    populateEmployeeFilter() {
        const select = document.getElementById('presenceEmployee');
        if (!select) return;

        // Kosongkan dropdown terlebih dahulu
        select.innerHTML = '';
        
        // Gunakan data dari API atau fallback ke data statis
        const employees = this.employees.length > 0 ? this.employees : 
            ['Karyawan1', 'Karyawan2', 'Karyawan3', 'Karyawan4', 'Karyawan5'];

        // Buat option untuk setiap karyawan
        employees.forEach(emp => {
            const option = document.createElement('option');
            option.value = emp.name || emp;
            option.textContent = emp.name || emp;
            select.appendChild(option);
        });

        // Refresh Select2 jika sudah diinisialisasi
        if (select._select2) {
            $(select).trigger('change.select2');
        }
    }

    /**
     * Setup semua event listeners yang diperlukan
     */
    setupEventListeners() {
        // Event listener untuk tombol apply filter
        document.getElementById('applyFilterModal')?.addEventListener('click', () => this.applyFilters());
        
        // Event listener untuk tombol reset filter
        document.getElementById('resetFilter')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.resetFilters();
        });

        // Event listener ketika modal filter dibuka
        document.getElementById('staticBackdrop')?.addEventListener('show.bs.modal', () => {
            if (this.employees.length === 0) this.loadEmployees();
        });
    }

    /**
     * Memuat data presensi dari API dengan filter dan pagination
     * @param {Object} filters - Filter data (tanggal, karyawan)
     * @param {number} page - Halaman yang dimuat
     */
    async loadPresences(filters = {}, page = 1) {
        try {
            this.showLoading();
            this.currentPage = page;
            this.currentFilters = filters;
            
            // Build query parameters
            const queryParams = new URLSearchParams();
            if (filters.start_date) queryParams.append('start_date', filters.start_date);
            if (filters.end_date) queryParams.append('end_date', filters.end_date);
            if (filters.employees) filters.employees.forEach(emp => queryParams.append('employees[]', emp));
            
            // Tambah parameter pagination
            queryParams.append('page', page);
            queryParams.append('per_page', this.perPage);
            
            // Fetch data dari API
            const url = `${this.baseUrl}?${queryParams}`;
            const response = await window.fetchWithAuth(url, { method: 'GET' });
            const result = await response.json();
            
            if (result.status === 'success') {
                this.handleSuccessResponse(result);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            this.showError('Gagal memuat data absensi: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Handle response sukses dari API
     * @param {Object} result - Data response dari API
     */
    handleSuccessResponse(result) {
        // Jika backend support pagination metadata
        if (result.data && result.meta) {
            this.totalPages = result.meta.last_page;
            this.renderPresences(result.data.data || result.data);
        } else {
            // Fallback ke client-side pagination
            this.handleClientSidePagination(result.data);
        }
    }

    /**
     * Handle pagination di client-side ketika backend tidak support
     * @param {Array} allData - Semua data presensi
     */
    handleClientSidePagination(allData) {
        this.totalPages = Math.ceil(allData.length / this.perPage);
        const startIndex = (this.currentPage - 1) * this.perPage;
        const paginatedData = allData.slice(startIndex, startIndex + this.perPage);
        this.renderPresences(paginatedData);
    }

    // ========== RENDER METHODS ==========

    /**
     * Render data presensi ke dalam tabel
     * @param {Array} presences - Data presensi yang akan di-render
     */
    renderPresences(presences) {
        if (!this.tableBody) return;

        // Jika tidak ada data, tampilkan pesan kosong
        if (!presences?.length) {
            this.tableBody.innerHTML = this.getNoDataTemplate();
            this.renderPagination();
            return;
        }

        // Render setiap baris data
        this.tableBody.innerHTML = presences.map((presence, index) => this.getPresenceRow(presence, index)).join('');
        this.renderPagination();
    }

    /**
     * Generate HTML untuk satu baris data presensi
     * @param {Object} presence - Data presensi individual
     * @param {number} index - Index data dalam halaman saat ini
     * @returns {string} HTML string untuk satu baris tabel
     */
    getPresenceRow(presence, index) {
        const globalIndex = ((this.currentPage - 1) * this.perPage) + index + 1;
        const { check_in_photo, check_out_photo, attendance_status } = presence;
        
        return `
            <tr>
                <td class="text-dark text-center">${globalIndex}</td>
                <td class="text-dark text-center">${this.formatDate(presence.date)}</td>
                <td class="text-dark text-center">${this.escapeHtml(presence.employee_name)}</td>
                <td class="text-dark text-center">${this.formatTime(presence.check_in_time)}</td>
                <td class="text-dark text-center">${presence.check_out_time ? this.formatTime(presence.check_out_time) : 'Belum Absen Pulang'}</td>
                <td class="text-dark text-center">${presence.total_hours || '-'}</td>
                <td class="text-center">${this.getPhotoButton(check_in_photo, 'Masuk')}</td>
                <td class="text-center">${this.getPhotoButton(check_out_photo, 'Pulang')}</td>
                <td class="text-dark text-center">${this.getStatusBadge(attendance_status)}</td>
                <td class="text-center">${this.getActionDropdown(presence.attendance_id, globalIndex)}</td>
            </tr>
        `;
    }

    /**
     * Generate tombol untuk melihat foto absensi
     * @param {string} photo - URL foto
     * @param {string} type - Jenis absensi (Masuk/Pulang)
     * @returns {string} HTML string untuk tombol foto
     */
    getPhotoButton(photo, type) {
        return photo ? 
            `<button type="button" class="btn btn-sm-absen btn-info-custom" onclick="presenceData.openPhotoModal('${photo}', '${type}')">Buka Foto</button>` : 
            '<span class="text-muted">-</span>';
    }

    /**
     * Generate badge status absensi dengan warna yang sesuai
     * @param {string} status - Status absensi (Hadir, Terlambat, dll)
     * @returns {string} HTML string untuk badge status
     */
    getStatusBadge(status) {
        const statusColors = {
            'Hadir': '#28a745', 
            'Terlambat': '#ffc107', 
            'Tidak Hadir': '#dc3545',
            'Izin': '#17a2b8', 
            'Cuti': '#6f42c1'
        };
        const color = statusColors[status] || '#6c757d';
        return `<div style="width: 65px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 20px; background-color: ${color}; color: white; font-size: 12px;">${status || 'Hadir'}</div>`;
    }

    /**
     * Generate dropdown menu untuk aksi detail
     * @param {string} attendanceId - ID absensi
     * @param {number} index - Index untuk ID dropdown yang unik
     * @returns {string} HTML string untuk dropdown menu
     */
    getActionDropdown(attendanceId, index) {
        return `
            <div class="dropdown show">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink${index}" data-toggle="dropdown">Detail</a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink${index}">
                    <a class="dropdown-item" href="#" onclick="presenceData.viewLocation('checkin', '${attendanceId}')"><i class="fas fa-arrow-right"></i> Lihat Lokasi Masuk</a>
                    <a class="dropdown-item" href="#" onclick="presenceData.viewLocation('checkout', '${attendanceId}')"><i class="fas fa-arrow-left"></i> Lihat Lokasi Pulang</a>
                </div>
            </div>
        `;
    }

    /**
     * Template untuk menampilkan pesan ketika tidak ada data
     * @returns {string} HTML string untuk pesan data kosong
     */
    getNoDataTemplate() {
        return `
            <tr>
                <td colspan="10" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                        <p>Tidak ada data absensi</p>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Render pagination controls
     * Menampilkan tombol previous, page numbers, dan next
     */
    renderPagination() {
        if (!this.paginationContainer) return;

        let html = '';
        
        // Tombol Previous (disabled jika di halaman pertama)
        html += `<li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage - 1}"><i class="fas fa-chevron-left"></i></a>
        </li>`;

        // Number pagination (semua halaman)
        for (let i = 1; i <= this.totalPages; i++) {
            html += `<li class="page-item ${i === this.currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        // Tombol Next (disabled jika di halaman terakhir)
        html += `<li class="page-item ${this.currentPage === this.totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage + 1}"><i class="fas fa-chevron-right"></i></a>
        </li>`;

        this.paginationContainer.innerHTML = html;
        this.addPaginationEventListeners();
    }

    /**
     * Tambah event listeners untuk tombol pagination
     */
    addPaginationEventListeners() {
        this.paginationContainer.querySelectorAll('.page-link:not(.disabled)').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.getAttribute('data-page'));
                if (page && page !== this.currentPage) {
                    this.loadPresences(this.currentFilters, page);
                }
            });
        });
    }

    /**
     * Apply filter yang dipilih user
     * Memuat data dengan filter dan reset ke halaman 1
     */
    applyFilters() {
        const filters = {
            start_date: document.getElementById('fromDate')?.value,
            end_date: document.getElementById('toDate')?.value,
            employees: Array.from(document.getElementById('presenceEmployee')?.selectedOptions || []).map(opt => opt.value)
        };

        this.loadPresences(filters, 1);
        $('#staticBackdrop').modal('hide');
    }

    /**
     * Reset semua filter ke keadaan awal
     */
    resetFilters() {
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        
        const employeeSelect = document.getElementById('presenceEmployee');
        if (employeeSelect) {
            employeeSelect.value = '';
            if (employeeSelect._select2) $(employeeSelect).trigger('change');
        }

        this.loadPresences({}, 1);
    }

    // ========== UTILITY METHODS ==========

    /**
     * Buka modal untuk menampilkan foto absensi
     * @param {string} photoUrl - URL foto yang akan ditampilkan
     * @param {string} type - Jenis absensi (Masuk/Pulang)
     */
    openPhotoModal(photoUrl, type) {
        const modalBody = document.querySelector('#OpenFotoModal .modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center">
                    <h6>Foto ${type}</h6>
                    <img src="${photoUrl}" alt="Foto ${type}" class="img-fluid rounded" style="max-height: 400px;">
                    <div class="mt-3">
                        <a href="${photoUrl}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                    </div>
                </div>
            `;
            $('#OpenFotoModal').modal('show');
        }
    }

    /**
     * Handler untuk melihat lokasi absensi (placeholder)
     * @param {string} type - Jenis lokasi (checkin/checkout)
     * @param {string} attendanceId - ID absensi
     */
    viewLocation(type, attendanceId) {
        alert(`Fitur lihat lokasi ${type} untuk absensi ID: ${attendanceId}`);
    }

    /**
     * Format tanggal ke format Indonesia (DD/MM/YYYY)
     * @param {string} dateString - String tanggal
     * @returns {string} Tanggal yang diformat
     */
    formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID');
    }

    /**
     * Format waktu ke format HH:MM:SS
     * @param {string} timeString - String waktu
     * @returns {string} Waktu yang diformat
     */
    formatTime(timeString) {
        if (!timeString) return '-';
        // Jika sudah dalam format jam, return langsung
        if (timeString.match(/^\d{1,2}:\d{2}:\d{2}$/)) return timeString;
        
        // Jika mengandung tanggal, extract bagian waktu saja
        const date = new Date(timeString);
        return isNaN(date.getTime()) ? timeString : date.toLocaleTimeString('id-ID');
    }

    /**
     * Escape HTML characters untuk mencegah XSS
     * @param {string} unsafe - String yang belum di-escape
     * @returns {string} String yang sudah di-escape
     */
    escapeHtml(unsafe) {
        return unsafe?.toString().replace(/[&<>"']/g, m => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[m])) || '-';
    }

    /**
     * Tampilkan loading state di tabel
     */
    showLoading() {
        if (this.tableBody) {
            this.tableBody.innerHTML = `
                <tr><td colspan="10" class="text-center py-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <span class="ms-2">Memuat data absensi...</span>
                    </div>
                </td></tr>
            `;
        }
    }

    /**
     * Sembunyikan loading state (kosongkan karena dihandle oleh render)
     */
    hideLoading() {}

    /**
     * Tampilkan pesan error
     * @param {string} message - Pesan error
     */
    showError(message) { 
        this.showAlert(message, 'danger'); 
    }

    /**
     * Tampilkan pesan sukses
     * @param {string} message - Pesan sukses
     */
    showSuccess(message) { 
        this.showAlert(message, 'success'); 
    }

    /**
     * Tampilkan alert message
     * @param {string} message - Pesan yang akan ditampilkan
     * @param {string} type - Tipe alert (success, danger, dll)
     */
    showAlert(message, type) {
        // Hapus alert yang sudah ada
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Buat alert baru
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.margin = '20px';
        alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;

        // Tambahkan alert ke DOM
        document.querySelector('.card-body')?.insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
    }
}

// Initialize class ketika DOM siap
document.addEventListener('DOMContentLoaded', () => {
    window.presenceData = new PresenceData();
});