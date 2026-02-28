class PresenceProjectManager {
    constructor() {
        this.baseUrl = '/api/presence-pm';
        this.tableBody = document.querySelector('#kegiatanTable tbody');
        this.paginationContainer = document.querySelector('.pagination');
        this.currentPage = 1;
        this.perPage = 10;
        this.totalPages = 0;
        this.currentFilters = {};
        this.init();
    }

    init() {
        this.loadPresences();
        this.setupEventListeners();
    }

    setupEventListeners() {
        const applyFilterBtn = document.getElementById('applyFilterModal');
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', () => {
                this.applyFilters();
            });
        }

        const resetFilterBtn = document.getElementById('resetFilter');
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetFilters();
            });
        }

        const searchInput = document.querySelector('.card-header-form input');
        const searchBtn = document.querySelector('.card-header-form button');
        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleSearch(searchInput.value);
            });

            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.handleSearch(searchInput.value);
                }
            });
        }
    }

    async loadPresences(filters = {}, page = 1) {
        try {
            this.showLoading();
            this.currentPage = page;
            this.currentFilters = filters;

            let url = this.baseUrl;
            const queryParams = new URLSearchParams();
            if (filters.start_date) queryParams.append('start_date', filters.start_date);
            if (filters.end_date) queryParams.append('end_date', filters.end_date);
            if (filters.search) queryParams.append('search', filters.search);
            queryParams.append('page', page);
            queryParams.append('per_page', this.perPage);
            if (queryParams.toString()) url += `?${queryParams.toString()}`;

            const response = await window.fetchWithAuth(url, { method: 'GET' });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const result = await response.json();

            if (result.success) {
                this.handleSuccessResponse(result);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error loading presences:', error);
            this.showError('Gagal memuat data absensi: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    handleSuccessResponse(result) {
        const presences = result.data || [];

        if (result.meta) {
            this.totalPages = result.meta.last_page || 1;
            this.renderPresences(presences);
        } else {
            this.handleClientSidePagination(presences);
        }
    }

    handleClientSidePagination(allData) {
        this.totalPages = Math.ceil(allData.length / this.perPage);
        const startIndex = (this.currentPage - 1) * this.perPage;
        const paginatedData = allData.slice(startIndex, startIndex + this.perPage);
        this.renderPresences(paginatedData);
    }

    renderPresences(presences) {
        if (!this.tableBody) return;

        if (!presences || presences.length === 0) {
            this.tableBody.innerHTML = this.getNoDataTemplate();
            this.renderPagination();
            return;
        }

        this.tableBody.innerHTML = presences.map((presence, index) => this.getPresenceRow(presence, index)).join('');
        this.renderPagination();
    }

    getPresenceRow(presence, index) {
        const globalIndex = ((this.currentPage - 1) * this.perPage) + index + 1;
        const checkInTime = this.formatTime(presence.check_in);
        const checkOutTime = presence.check_out ? this.formatTime(presence.check_out) : 'Belum Absen Pulang';
        const totalHours = presence.total_hours || this.calculateTotalHours(presence.check_in, presence.check_out);
        const status = presence.attendance_status || this.getAttendanceStatus(presence.check_in, presence.check_out);

        return `
            <tr>
                <td class="text-dark text-center">${globalIndex}</td>
                <td class="text-dark text-center">${this.formatDate(presence.date)}</td>
                <td class="text-dark text-center">${checkInTime}</td>
                <td class="text-dark text-center">${checkOutTime}</td>
                <td class="text-dark text-center">${totalHours}</td>
                <td class="text-center">${this.getPhotoButton(presence.in_photo, 'Masuk')}</td>
                <td class="text-center">${this.getPhotoButton(presence.out_photo, 'Pulang')}</td>
                <td class="text-center">
                    <div class="text-dark">${status}</div>
                </td>
                <td class="text-center">
                    <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink${globalIndex}" data-toggle="dropdown">
                            Detail
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink${globalIndex}">
                            <a class="dropdown-item" href="#" onclick="presenceKaryawan.viewLocation('checkin', '${presence.id}')">
                                <i class="fas fa-arrow-right"></i> Lihat Lokasi Masuk
                            </a>
                            <a class="dropdown-item" href="#" onclick="presenceKaryawan.viewLocation('checkout', '${presence.id}')">
                                <i class="fas fa-arrow-left"></i> Lihat Lokasi Pulang
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }

    getPhotoButton(photo, type) {
        if (photo) {
            const photoUrl = photo.startsWith('http') ? photo : `/storage/${photo}`;
            return `
                <button type="button" class="btn btn-sm-absen btn-info-custom"
                    onclick="presenceKaryawan.openPhotoModal('${photoUrl}', '${type}')">
                    Buka Foto
                </button>
            `;
        }
        return '<span class="text-muted">-</span>';
    }

    getNoDataTemplate() {
        return `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                        <p>Tidak ada data absensi</p>
                    </div>
                </td>
            </tr>
        `;
    }

    renderPagination() {
        let html = '';

        html += `<li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage - 1}">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>`;

        for (let i = 1; i <= this.totalPages; i++) {
            html += `<li class="page-item ${i === this.currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        html += `<li class="page-item ${this.currentPage === this.totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${this.currentPage + 1}">
                <i class="fas fa-chevron-right"></i>
            </a>
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
            end_date: document.getElementById('toDate')?.value
        };

        this.loadPresences(filters, 1);
        $('#staticBackdrop').modal('hide');
    }

    resetFilters() {
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        this.loadPresences({}, 1);
    }

    handleSearch(searchTerm) {
        const filters = {
            search: searchTerm.trim()
        };
        this.loadPresences(filters, 1);
    }

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

    viewLocation(type, presenceId) {
        alert(`Fitur lihat lokasi ${type} untuk absensi ID: ${presenceId}`);
    }

    formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID');
    }

    formatTime(timeString) {
        if (!timeString) return '-';
        if (timeString.match(/^\d{1,2}:\d{2}:\d{2}$/)) return timeString;

        const date = new Date(timeString);
        return isNaN(date.getTime()) ? timeString : date.toLocaleTimeString('id-ID');
    }

    /**
     * Hitung total jam kerja
     * Mendukung format "HH:MM:SS" (dari backend) maupun datetime penuh.
     */
    calculateTotalHours(checkIn, checkOut) {
        if (!checkIn || !checkOut) return '-';

        try {
            const toDate = (time) => {
                // Jika hanya waktu (misal "09:00:00"), gunakan tanggal dummy
                if (typeof time === 'string' && time.match(/^\d{1,2}:\d{2}(:\d{2})?$/)) {
                    const [h, m, s = '0'] = time.split(':').map(Number);
                    const d = new Date();
                    d.setHours(h, m, s, 0);
                    return d;
                }
                const d = new Date(time);
                return isNaN(d.getTime()) ? null : d;
            };

            const start = toDate(checkIn);
            const end = toDate(checkOut);
            if (!start || !end) return '-';

            const diffMs = end - start;
            if (diffMs <= 0) return '-';

            const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

            return `${diffHrs} jam ${diffMins} menit`;
        } catch (error) {
            return '-';
        }
    }

    getAttendanceStatus(checkIn, checkOut) {
        if (!checkIn) return 'Tidak Hadir';
        if (!checkOut) return 'Belum Pulang';

        const checkInTime = new Date(checkIn);
        const hour = checkInTime.getHours();

        if (hour > 9) return 'Terlambat';
        return 'Hadir';
    }

    escapeHtml(unsafe) {
        return unsafe?.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;") || '';
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

    showError(message) {
        this.showAlert(message, 'danger');
    }

    showSuccess(message) {
        this.showAlert(message, 'success');
    }

    showAlert(message, type) {
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.margin = '20px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.insertBefore(alertDiv, cardBody.firstChild);
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.presenceKaryawan = new PresenceKaryawan();
});
