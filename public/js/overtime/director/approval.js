class DirectorOvertimeApproval {
    constructor() {
        this.baseListUrl = '/api/director/overtimes/approval-list';
        this.approveUrl = (id) => `/api/director/overtimes/${id}/approve`;
        this.rejectUrl = (id) => `/api/director/overtimes/${id}/reject`;
        this.tableBody = document.querySelector('.table.table-bordered tbody');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.init();
    }

    init() {
        if (!this.tableBody) return;
        this.loadList();
    }

    showLoading() {
        this.tableBody.innerHTML = `
            <tr class="text-center overtime-data">
                <td colspan="7" class="py-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Memuat data pengajuan lembur...</span>
                    </div>
                </td>
            </tr>
        `;
    }

    showError(message) {
        this.tableBody.innerHTML = `
            <tr class="text-center overtime-data">
                <td colspan="7" class="py-4 text-danger">
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

    escapeHtml(unsafe) {
        return (unsafe || '')
            .toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    async loadList() {
        try {
            this.showLoading();

            const response = await fetch(this.baseListUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

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
                        <td colspan="7" class="py-4 text-muted">
                            Tidak ada pengajuan lembur yang perlu disetujui.
                        </td>
                    </tr>
                `;
                return;
            }

            this.tableBody.innerHTML = data.map((item, index) => {
                const proofCell = item.proof_url
                    ? `<a href="${item.proof_url}" target="_blank" class="evidence" style="text-decoration: none">Buka Lampiran</a>`
                    : '-';

                return `
                    <tr class="overtime-data">
                        <td class="text-center">${index + 1}</td>
                        <td class="text-center">${item.date || '-'}</td>
                        <td class="text-center">${this.escapeHtml(item.employee_name || '-')}</td>
                        <td>
                            <div class="space-td">${this.escapeHtml(item.description || '-')}</div>
                        </td>
                        <td class="text-center">${proofCell}</td>
                        <td class="text-center">${this.formatStatusBadge(item.status)}</td>
                        <td class="text-center">
                            ${item.status === 'pending'
                                ? `<div class="overtime-action space-td">
                                       <button type="button" class="btn btn-success btn-approve" data-overtime-id="${item.id}">
                                           Setujui
                                       </button>
                                       <button type="button" class="btn btn-danger btn-reject" data-overtime-id="${item.id}">
                                           Tolak
                                       </button>
                                   </div>`
                                : ''}
                        </td>
                    </tr>
                `;
            }).join('');

            this.attachEvents();
        } catch (error) {
            console.error('Error loading Director overtime approval list:', error);
            this.showError('Gagal memuat data lembur.');
        }
    }

    attachEvents() {
        this.tableBody.querySelectorAll('.btn-approve').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-overtime-id');
                this.handleApprove(id);
            });
        });

        this.tableBody.querySelectorAll('.btn-reject').forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-overtime-id');
                this.handleReject(id);
            });
        });
    }

    async handleApprove(id) {
        if (!id) return;
        const confirmed = window.confirm('Yakin ingin MENYETUJUI pengajuan lembur ini?');
        if (!confirmed) return;

        const reason = window.prompt('Alasan persetujuan (opsional):', '') || '';
        await this.sendDecision(this.approveUrl(id), reason, 'Pengajuan lembur berhasil disetujui.');
    }

    async handleReject(id) {
        if (!id) return;
        const confirmed = window.confirm('Yakin ingin MENOLAK pengajuan lembur ini?');
        if (!confirmed) return;

        const reason = window.prompt('Alasan penolakan (opsional):', '') || '';
        await this.sendDecision(this.rejectUrl(id), reason, 'Pengajuan lembur berhasil ditolak.');
    }

    async sendDecision(url, reason, successMessage) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ reason })
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok || result.status !== 'success') {
                throw new Error(result.message || 'Gagal mengubah status lembur');
            }

            alert(successMessage);
            this.loadList();
        } catch (error) {
            console.error('Error updating overtime status:', error);
            alert('Terjadi kesalahan saat mengubah status lembur.');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('.table.table-bordered tbody');
    if (tableBody) {
        new DirectorOvertimeApproval();
    }
});

