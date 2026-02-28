class ActivityTypes {
    constructor() {
        this.baseUrl = '/api/activity-types';
        this.tableBody = document.querySelector('#kegiatanTable tbody');
        this.init();
    }

    /**
     * Inisialisasi utama
     * Memuat data activity types dan setup event listeners
     */
    init() {
        this.loadActivityTypes();
        this.setupEventListeners();
    }

    /**
     * Setup semua event listeners
     */
    setupEventListeners() {
        // Event listener untuk tombol tambah
        document.querySelector('#addActivityModal .btn-primary')?.addEventListener('click', () => this.handleAdd());

        // Event listener untuk tombol edit
        document.querySelector('#editActivityModal .btn-secondary')?.addEventListener('click', () => this.handleUpdate());

        // Event listener untuk tombol hapus
        document.querySelector('#ModalHapus .btn-primary')?.addEventListener('click', () => this.handleDelete());

        // Event listener untuk modal edit (populate data)
        $('#editActivityModal').on('show.bs.modal', (event) => {
            const button = $(event.relatedTarget);
            const activityName = button.data('activity-name');
            const activityId = button.data('activity-id');

            console.log('Edit modal opened - ID:', activityId, 'Name:', activityName);

            const modal = $(event.target);
            modal.find('#activityName').val(activityName);
            modal.find('#activityForm').data('activity-id', activityId);
        });

        // Event listener untuk modal hapus (set ID)
        $('#ModalHapus').on('show.bs.modal', (event) => {
            const button = event.relatedTarget; 
        
            const row = button.closest('tr');
            const editButton = row.querySelector('.btn-secondary');
            const activityId = editButton.dataset.activityId;
            
            console.log('Delete modal opened - ID:', activityId);
            event.target.querySelector('.btn-primary').dataset.activityId = activityId;
        });

        // Clear form ketika modal tambah ditutup
        $('#addActivityModal').on('hidden.bs.modal', () => {
            document.querySelector('#addActivityModal #activityName').value = '';
        });
    }

    /**
     * Memuat data activity types dari API
     */
    async loadActivityTypes() {
        try {
            this.showLoading();

            const response = await fetch(this.baseUrl, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const result = await response.json();

            if (result.status === 'success') {
                this.renderActivityTypes(result.data);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error loading activity types:', error);
            this.showError('Gagal memuat data jenis kegiatan');
        }
    }

    /**
     * Render data activity types ke tabel
     * @param {Array} activities - Data activity types
     */
    renderActivityTypes(activities) {
        if (!this.tableBody) return;

        if (!activities || activities.length === 0) {
            this.tableBody.innerHTML = this.getNoDataTemplate();
            return;
        }

        this.tableBody.innerHTML = activities.map((activity, index) => this.getActivityRow(activity, index)).join('');
    }

    /**
     * Generate HTML untuk satu baris data activity type
     * @param {Object} activity - Data activity type
     * @param {number} index - Index data
     * @returns {string} HTML string untuk satu baris tabel
     */
    getActivityRow(activity, index) {
        return `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td class="text-center">${this.escapeHtml(activity.name)}</td>
                <td class="text-center">
                    <button class="btn btn-secondary text-dark" data-toggle="modal"
                        data-target="#editActivityModal" data-activity-id="${activity.id}"
                        data-activity-name="${this.escapeHtml(activity.name)}" style="width: 5rem">
                        Edit
                    </button>
                    <button type="button" class="btn btn-filter" style="width: 5rem"
                        data-toggle="modal" data-target="#ModalHapus">
                        Hapus
                    </button>
                </td>
            </tr>
        `;
    }

    /**
     * Template untuk menampilkan pesan ketika tidak ada data
     * @returns {string} HTML string untuk pesan data kosong
     */
    getNoDataTemplate() {
        return `
            <tr>
                <td colspan="3" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-tasks fa-2x mb-2"></i>
                        <p>Belum ada jenis kegiatan</p>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Handle tambah activity type baru
     */
    async handleAdd() {
        const name = document.querySelector('#addActivityModal #activityName')?.value.trim();

        if (!name) {
            this.showError('Nama jenis kegiatan harus diisi');
            return;
        }

        try {
            const response = await fetch(this.baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ name: name })
            });

            const result = await response.json();

            if (result.status === 'success') {
                this.showSuccess('Jenis kegiatan berhasil ditambahkan');
                $('#addActivityModal').modal('hide');
                this.loadActivityTypes(); // Reload data
            } else {
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                } else {
                    throw new Error(result.message);
                }
            }
        } catch (error) {
            console.error('Error adding activity type:', error);
            this.showError('Gagal menambah jenis kegiatan: ' + error.message);
        }
    }

    /**
     * Handle update activity type
     */
    async handleUpdate() {
        // PERBAIKAN: Akses data dengan cara yang benar
        const modal = $('#editActivityModal');
        const activityId = modal.find('#activityForm').data('activity-id');
        const name = modal.find('#activityName').val().trim();
    
        console.log('Update data - ID:', activityId, 'Name:', name);
    
        if (!activityId || !name) {
            this.showError('Data tidak valid');
            return;
        }
    
        try {
            // PERBAIKAN: Gunakan activityId yang benar (bukan activityTypesId)
            const response = await fetch(`${this.baseUrl}/${activityId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ name: name })
            });
    
            const result = await response.json();
    
            if (result.status === 'success') {
                this.showSuccess('Jenis kegiatan berhasil diupdate');
                $('#editActivityModal').modal('hide');
                this.loadActivityTypes(); // Reload data
            } else {
                if (result.errors) {
                    this.showValidationErrors(result.errors);
                } else {
                    throw new Error(result.message);
                }
            }
        } catch (error) {
            console.error('Error updating activity type:', error);
            this.showError('Gagal mengupdate jenis kegiatan: ' + error.message);
        }
    }

    /**
     * Handle delete activity type
     */
    async handleDelete() {
        const modal = document.getElementById('ModalHapus');
        const activityId = modal.querySelector('.btn-primary')?.dataset.activityId;

        console.log(activityId);
        if (!activityId) {
            this.showError('ID kegiatan tidak ditemukan');
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/${activityId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const result = await response.json();

            if (result.status === 'success') {
                this.showSuccess('Jenis kegiatan berhasil dihapus');
                $('#ModalHapus').modal('hide');
                this.loadActivityTypes(); // Reload data
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error deleting activity type:', error);
            this.showError('Gagal menghapus jenis kegiatan: ' + error.message);
        }
    }

    /**
     * Tampilkan error validasi dari backend
     * @param {Object} errors - Object errors dari validasi
     */
    showValidationErrors(errors) {
        let errorMessage = 'Validation failed: ';
        Object.keys(errors).forEach(field => {
            errorMessage += errors[field][0] + ' ';
        });
        this.showError(errorMessage);
    }

    // ========== UTILITY METHODS ==========

    /**
     * Escape HTML characters untuk mencegah XSS
     * @param {string} unsafe - String yang belum di-escape
     * @returns {string} String yang sudah di-escape
     */
    escapeHtml(unsafe) {
        return unsafe?.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;") || '';
    }

    /**
     * Tampilkan loading state di tabel
     */
    showLoading() {
        if (this.tableBody) {
            this.tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center py-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="ms-2">Memuat data jenis kegiatan...</span>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

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
        // Hapus alert yang sudah ada - PERBAIKAN: gunakan selector yang tepat
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => {
            // Hanya hapus alert yang dibuat oleh class ini, bukan semua alert
            if (alert.parentNode) {
                alert.remove();
            }
        });

        // Buat alert baru
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.margin = '20px';
        alertDiv.style.zIndex = '9999'; // Pastikan di atas elemen lain
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Tambahkan alert ke DOM
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.insertBefore(alertDiv, cardBody.firstChild);
        }

        // Auto remove setelah 5 detik - TAMBAHKAN INI
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Initialize class ketika DOM siap
document.addEventListener('DOMContentLoaded', () => {
  window.activityTypes = new ActivityTypes();
});
