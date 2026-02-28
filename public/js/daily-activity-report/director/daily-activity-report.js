class DailyActivityReport {
    constructor() {
        this.baseUrl = '/api/daily-activities';
        this.employeesUrl = '/api/data-master/users';
        this.projectsUrl = '/api/projects';
        this.init();
    }

    init() {
        this.loadEmployees();
        this.loadProjects();
        this.setupEventListeners();
    }

    /**
     * Memuat data karyawan dari API
     */
    async loadEmployees() {
        try {
            const response = await fetch(this.employeesUrl);
            const result = await response.json();
            
            if (result.status === 'success') {
                this.populateEmployeeSelect(result.data);
            }
        } catch (error) {
            console.error('Error loading employees:', error);
            this.showError('Gagal memuat data karyawan', 3000);
        }
    }

    /**
     * Memuat data project dari API
     */
    async loadProjects() {
        try {
            const response = await fetch(this.projectsUrl);
            const result = await response.json();
            
            if (result.status === 'success') {
                this.populateProjectSelect(result.data);
            }
        } catch (error) {
            console.error('Error loading projects:', error);
            this.showError('Gagal memuat data project', 3000);
        }
    }

    /**
     * Mengisi dropdown karyawan
     */
    populateEmployeeSelect(employees) {
        const select = document.getElementById('activityEmployee');
        if (!select) return;

        employees.forEach(employee => {
            const option = document.createElement('option');
            option.value = employee.id;
            option.textContent = employee.name;
            select.appendChild(option);
        });

        if (select._select2) {
            $(select).trigger('change.select2');
        }
    }

    /**
     * Mengisi dropdown project
     */
    populateProjectSelect(projects) {
        const select = document.getElementById('activityProject');
        if (!select) return;

        projects.forEach(project => {
            const option = document.createElement('option');
            option.value = project.id_project;
            option.textContent = project.name;
            select.appendChild(option);
        });

        if (select._select2) {
            $(select).trigger('change.select2');
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        const generateBtn = document.getElementById('generatePdfBtn');
        if (generateBtn) {
            generateBtn.addEventListener('click', () => this.generatePDF());
        }
    }

    /**
     * Generate PDF report dan buka di tab baru seperti Chrome PDF viewer
     */
    async generatePDF() {
        const formData = new FormData(document.getElementById('reportForm'));
        const data = Object.fromEntries(formData.entries());

        // Validasi form
        if (!data.start_date || !data.end_date) {
            this.showError('Harap isi tanggal mulai dan tanggal selesai', 3000);
            return;
        }

        try {
            this.showLoading();

            // Build URL dengan query parameters
            const queryParams = new URLSearchParams();
            queryParams.append('start_date', data.start_date);
            queryParams.append('end_date', data.end_date);
            if (data.employee_id) queryParams.append('employee_id', data.employee_id);
            if (data.project_id) queryParams.append('project_id', data.project_id);

            // Buka PDF di tab baru dengan stream (Chrome-like viewer)
            const pdfUrl = `/api/generate-pdf?${queryParams.toString()}`;
            
            // Buka di tab baru
            const newWindow = window.open(pdfUrl, '_blank');
            
            if (newWindow) {
                this.showSuccess('Laporan berhasil dibuka di tab baru', 2000);
            } else {
                throw new Error('Popup diblokir oleh browser. Izinkan popup untuk situs ini.');
            }

        } catch (error) {
            console.error('Error generating PDF:', error);
            this.showError('Gagal membuka laporan: ' + error.message, 5000);
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Tampilkan loading state
     */
    showLoading() {
        const btn = document.getElementById('generatePdfBtn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
        }
    }

    /**
     * Sembunyikan loading state
     */
    hideLoading() {
        const btn = document.getElementById('generatePdfBtn');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('btn-loading');
            btn.querySelector('.btn-text').classList.remove('d-none');
            btn.querySelector('.spinner-border').classList.add('d-none');
        }
    }

    /**
     * Tampilkan pesan error dengan auto-hide
     */
    showError(message, duration = 5000) {
        this.showAlert(message, 'danger', duration);
    }

    /**
     * Tampilkan pesan sukses dengan auto-hide
     */
    showSuccess(message, duration = 3000) {
        this.showAlert(message, 'success', duration);
    }

    /**
     * Tampilkan alert dengan auto-hide
     */
    showAlert(message, type, duration) {
        // Hapus alert yang sudah ada
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Buat alert baru
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.margin = '20px';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Tambahkan alert ke DOM
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.insertBefore(alertDiv, cardBody.firstChild);
        }

        // Auto-hide setelah duration
        if (duration > 0) {
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, duration);
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.dailyActivityReport = new DailyActivityReport();
});