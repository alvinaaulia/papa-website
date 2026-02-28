class EmployeeOvertimeHistory {
    constructor() {
        this.baseUrl = '/api/overtime-employee';
        this.tableBody = document.querySelector('.table.table-bordered tbody');
        this.init();
    }

    init() {
        if (!this.tableBody) {
            return;
        }
        this.loadHistory();
    }

    showLoading() {
        this.tableBody.innerHTML = `
            <tr class="text-center overtime-data">
                <td colspan="8" class="py-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Memuat data lembur...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    showError(message) {
        this.tableBody.innerHTML = `
            <tr class="text-center overtime-data">
                <td colspan="8" class="py-4 text-danger">
                    ${message}
                </td>
            </tr>
        `;
    }

    formatStatusBadge(status) {
        const map = {
            pending: { text: 'Menunggu', class: 'badge-warning' },
            approved: { text: 'Disetujui', class: 'badge-success' },
            rejected: { text: 'Ditolak', class: 'badge-danger' }
        };
        const data = map[status] || { text: status || '-', class: 'badge-secondary' };
        return `<span class="badge badge-pill ${data.class} badge-custom">${data.text}</span>`;
    }

    formatTotalOvertime(totalMinutes) {
        if (typeof totalMinutes !== 'number') {
            return '-';
        }
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        if (hours && minutes) return `${hours} jam ${minutes} menit`;
        if (hours) return `${hours} jam`;
        return `${minutes} menit`;
    }

    async loadHistory() {
        try {
            this.showLoading();

            const response = await window.fetchWithAuth(this.baseUrl, { method: 'GET' });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.status !== 'success') {
                throw new Error(result.message || 'Gagal memuat data lembur');
            }

            const data = result.data || [];

            if (!data.length) {
                this.tableBody.innerHTML = `
                    <tr class="text-center overtime-data">
                        <td colspan="8" class="py-4 text-muted">
                            Belum ada riwayat lembur.
                        </td>
                    </tr>
                `;
                return;
            }

            this.tableBody.innerHTML = data.map((item, index) => {
                const jamLembur = `${item.start_overtime || '-'} s.d ${item.end_overtime || '-'}`;
                const totalJam = this.formatTotalOvertime(item.total_overtime);
                const proofCell = item.proof_url
                    ? `<a href="${item.proof_url}" target="_blank" class="evidence" style="text-decoration: none">Buka lampiran</a>`
                    : '-';

                return `
                    <tr class="overtime-data">
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${item.date || '-'}</td>
                        <td class="text-center">${jamLembur}</td>
                        <td class="text-center">${totalJam}</td>
                        <td><div class="space-td">${this.escapeHtml(item.description || '-')}</div></td>
                        <td class="text-center">${proofCell}</td>
                        <td class="text-center">${this.formatStatusBadge(item.status)}</td>
                        <td class="text-center"></td>
                    </tr>
                `;
            }).join('');
        } catch (error) {
            console.error('Error loading employee overtime history:', error);
            this.showError('Gagal memuat data lembur.');
        }
    }

    escapeHtml(unsafe) {
        return unsafe
            .toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('.table.table-bordered tbody');
    if (tableBody) {
        new EmployeeOvertimeHistory();
    }
});

