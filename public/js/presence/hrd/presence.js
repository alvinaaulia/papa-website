class PresenceData {
    constructor() {
        this.baseUrl = '/api/presence-hrd';
        this.employeesUrl = '/api/data-master/users';
        this.tableBody = document.querySelector('#kegiatanTable tbody');
        this.paginationContainer = document.querySelector('.pagination');

        this.currentPage = 1;
        this.perPage = 10;
        this.totalPages = 0;
        this.currentFilters = {};
        this.employees = [];

        this.init();
    }

    init() {
        this.loadEmployees();
        this.loadPresences();
        this.setupEventListeners();
    }

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

    populateEmployeeFilter() {
        const select = document.getElementById('presenceEmployee');
        if (!select) return;

        select.innerHTML = '';

        const employees = this.employees.length > 0 ? this.employees :
            ['Karyawan1', 'Karyawan2', 'Karyawan3', 'Karyawan4', 'Karyawan5'];
        employees.forEach(emp => {
            const option = document.createElement('option');
            option.value = emp.name || emp;
            option.textContent = emp.name || emp;
            select.appendChild(option);
        });

        if (select._select2) {
            $(select).trigger('change.select2');
        }
    }

    setupEventListeners() {
        document.getElementById('applyFilterModal')?.addEventListener('click', () => this.applyFilters());
        document.getElementById('resetFilter')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.resetFilters();
        });

        document.getElementById('staticBackdrop')?.addEventListener('show.bs.modal', () => {
            if (this.employees.length === 0) this.loadEmployees();
        });
    }

    /**
     * @param {Object} filters
     * @param {number} page
     */
    async loadPresences(filters = {}, page = 1) {
        try {
            this.showLoading();
            this.currentPage = page;
            this.currentFilters = filters;

            const queryParams = new URLSearchParams();
            if (filters.start_date) queryParams.append('start_date', filters.start_date);
            if (filters.end_date) queryParams.append('end_date', filters.end_date);
            if (filters.employees) filters.employees.forEach(emp => queryParams.append('employees[]', emp));

            queryParams.append('page', page);
            queryParams.append('per_page', this.perPage);

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
     * @param {Object} result
     */
    handleSuccessResponse(result) {
        if (result.data && result.meta) {
            this.totalPages = result.meta.last_page;
            this.renderPresences(result.data.data || result.data);
        } else {
            this.handleClientSidePagination(result.data);
        }
    }

    /**
     * Handle pagination di client-side ketika backend tidak support
     * @param {Array} allData
     */
    handleClientSidePagination(allData) {
        this.totalPages = Math.ceil(allData.length / this.perPage);
        const startIndex = (this.currentPage - 1) * this.perPage;
        const paginatedData = allData.slice(startIndex, startIndex + this.perPage);
        this.renderPresences(paginatedData);
    }

    /**
     * Render data presensi ke dalam tabel
     * @param {Array} presences
     */
    renderPresences(presences) {
        if (!this.tableBody) return;

        if (!presences?.length) {
            this.tableBody.innerHTML = this.getNoDataTemplate();
            this.renderPagination();
            return;
        }

        this.tableBody.innerHTML = presences.map((presence, index) => this.getPresenceRow(presence, index)).join('');
        this.renderPagination();
    }

    /**
     * Generate HTML untuk satu baris data presensi
     * @param {number} index
     * @returns {string}
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
     * @param {string} photo
     * @param {string} type
     * @returns {string}
     */
    getPhotoButton(photo, type) {
        return photo ?
            `<button type="button" class="btn btn-sm-absen btn-info-custom" onclick="presenceData.openPhotoModal('${photo}', '${type}')">Buka Foto</button>` :
            '<span class="text-muted">-</span>';
    }

    /**
     * Generate badge status absensi dengan warna yang sesuai
     * @param {string} status
     * @returns {string}
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
     * @param {string} attendanceId
     * @param {number} index
     * @returns {string}
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
     * @returns {string}
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

    renderPagination() {
        if (!this.paginationContainer) return;

        let html = '';
        html += `<li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage - 1}"><i class="fas fa-chevron-left"></i></a>
        </li>`;

        for (let i = 1; i <= this.totalPages; i++) {
            html += `<li class="page-item ${i === this.currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        html += `<li class="page-item ${this.currentPage === this.totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage + 1}"><i class="fas fa-chevron-right"></i></a>
        </li>`;

        this.paginationContainer.innerHTML = html;
        this.addPaginationEventListeners();
    }

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

    applyFilters() {
        const filters = {
            start_date: document.getElementById('fromDate')?.value,
            end_date: document.getElementById('toDate')?.value,
            employees: Array.from(document.getElementById('presenceEmployee')?.selectedOptions || []).map(opt => opt.value)
        };

        this.loadPresences(filters, 1);
        $('#staticBackdrop').modal('hide');
    }

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


    /**
     * Buka modal untuk menampilkan foto absensi
     * @param {string} photoUrl
     * @param {string} type
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
     * @param {string} type
     * @param {string} attendanceId
     */
    viewLocation(type, attendanceId) {
        alert(`Fitur lihat lokasi ${type} untuk absensi ID: ${attendanceId}`);
    }

    /**
     * Format tanggal ke format Indonesia (DD/MM/YYYY)
     * @param {string} dateString
     * @returns {string}
     */
    formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('id-ID');
    }

    /**
     * Format waktu ke format HH:MM:SS
     * @param {string} timeString
     * @returns {string}
     */
    formatTime(timeString) {
        if (!timeString) return '-';
        if (timeString.match(/^\d{1,2}:\d{2}:\d{2}$/)) return timeString;
        const date = new Date(timeString);
        return isNaN(date.getTime()) ? timeString : date.toLocaleTimeString('id-ID');
    }

    /**
     * Escape HTML characters untuk mencegah XSS
     * @param {string} unsafe
     * @returns {string}
     */
    escapeHtml(unsafe) {
        return unsafe?.toString().replace(/[&<>"']/g, m => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[m])) || '-';
    }

    /**
     * Tampilkan loading state di tabel (sama seperti role director)
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
     * @param {string} message
     */
    showSuccess(message) {
        this.showAlert(message, 'success');
    }

    /**
     * Tampilkan alert message
     * @param {string} message
     * @param {string} type
     */
    showAlert(message, type) {
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.margin = '20px';
        alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;

        document.querySelector('.card-body')?.insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.presenceData = new PresenceData();
});
