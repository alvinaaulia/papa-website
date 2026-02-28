class PmOvertimeForm {
    constructor() {
        this.submitButton = document.querySelector('.overtime-button .btn.btn-danger');
        this.fileInput = document.getElementById('inputEvidence');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.init();
    }

    init() {
        if (!this.submitButton) {
            return;
        }

        this.submitButton.addEventListener('click', (event) => {
            event.preventDefault();
            this.handleSubmit();
        });
    }

    showAlert(message, type = 'danger') {
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        const sectionBody = document.querySelector('.section-body') || document.body;
        sectionBody.insertBefore(alertDiv, sectionBody.firstChild);

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    validateInputs(date, start, end, description) {
        if (!date || !start || !end || !description) {
            this.showAlert('Tanggal, jam lembur, dan uraian kegiatan wajib diisi.', 'danger');
            return false;
        }
        return true;
    }

    async handleSubmit() {
        const date = document.getElementById('tgl-mulai')?.value;
        const start = document.getElementById('jamMulai')?.value;
        const end = document.getElementById('jamAkhir')?.value;
        const description = document.querySelector('textarea[name="alasan"]')?.value?.trim();

        if (!this.validateInputs(date, start, end, description)) {
            return;
        }

        const formData = new FormData();
        formData.append('date', date);
        formData.append('start_overtime', start);
        formData.append('end_overtime', end);
        formData.append('description', description);

        if (this.fileInput && this.fileInput.files.length > 0) {
            formData.append('proof', this.fileInput.files[0]);
        }

        const redirectUrl = this.submitButton.getAttribute('href') || '';

        try {
            this.submitButton.disabled = true;

            const response = await fetch('/api/pm/overtimes', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok || result.status !== 'success') {
                const message = result.message || 'Gagal mengirim pengajuan lembur.';
                this.showAlert(message, 'danger');
                this.submitButton.disabled = false;
                return;
            }

            this.showAlert('Pengajuan lembur berhasil dikirim.', 'success');

            if (redirectUrl) {
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 800);
            }
        } catch (error) {
            console.error('Error submitting overtime:', error);
            this.showAlert('Terjadi kesalahan saat mengirim pengajuan lembur.', 'danger');
            this.submitButton.disabled = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('.overtime-button .btn.btn-danger');
    if (button) {
        new PmOvertimeForm();
    }
});

